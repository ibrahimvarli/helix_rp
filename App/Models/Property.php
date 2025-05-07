<?php
namespace App\Models;

use App\Core\Model;

/**
 * Property Model
 */
class Property extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'properties';
    
    /**
     * Get all available properties for purchase
     * 
     * @param array $filters Optional filters
     * @return array Properties
     */
    public function getAvailableProperties($filters = [])
    {
        $query = "
            SELECT p.*, pt.name, pt.description, pt.price, pt.type
            FROM properties p
            JOIN property_types pt ON p.property_type_id = pt.id
            WHERE p.character_id IS NULL
        ";
        
        $params = [];
        
        // Add type filter if provided
        if (isset($filters['type'])) {
            $query .= " AND pt.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        // Add price range filter if provided
        if (isset($filters['min_price'])) {
            $query .= " AND pt.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (isset($filters['max_price'])) {
            $query .= " AND pt.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        // Add order by clause
        $query .= " ORDER BY pt.price ASC";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Purchase a property
     * 
     * @param int $propertyId Property ID
     * @param int $characterId Character ID
     * @param string $purchaseType Type of purchase (buy, rent)
     * @return bool Success status
     */
    public function purchaseProperty($propertyId, $characterId, $purchaseType = 'buy')
    {
        // Get property and property type details
        $stmt = $this->db->prepare("
            SELECT p.*, pt.price 
            FROM properties p
            JOIN property_types pt ON p.property_type_id = pt.id
            WHERE p.id = :property_id AND p.character_id IS NULL
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->execute();
        $property = $stmt->fetch();
        
        if (!$property) {
            return false; // Property not found or already owned
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Deduct money from character
            $characterModel = new Character();
            $success = $characterModel->removeMoney($characterId, $property['price']);
            
            if (!$success) {
                $this->db->rollBack();
                return false; // Not enough money
            }
            
            // Update property ownership
            $stmt = $this->db->prepare("
                UPDATE properties
                SET character_id = :character_id, 
                    purchase_date = :purchase_date,
                    purchase_type = :purchase_type
                WHERE id = :property_id
            ");
            $stmt->bindValue(':character_id', $characterId);
            $stmt->bindValue(':purchase_date', date('Y-m-d H:i:s'));
            $stmt->bindValue(':purchase_type', $purchaseType);
            $stmt->bindValue(':property_id', $propertyId);
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Get property details
     * 
     * @param int $propertyId Property ID
     * @return array|false Property data or false if not found
     */
    public function getPropertyDetails($propertyId)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, pt.name, pt.description, pt.price, pt.type, pt.size
            FROM properties p
            JOIN property_types pt ON p.property_type_id = pt.id
            WHERE p.id = :property_id
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Sell a property
     * 
     * @param int $propertyId Property ID
     * @param int $characterId Current owner character ID
     * @return bool Success status
     */
    public function sellProperty($propertyId, $characterId)
    {
        // Get property and verify ownership
        $stmt = $this->db->prepare("
            SELECT p.*, pt.price 
            FROM properties p
            JOIN property_types pt ON p.property_type_id = pt.id
            WHERE p.id = :property_id AND p.character_id = :character_id
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        $property = $stmt->fetch();
        
        if (!$property) {
            return false; // Property not found or not owned by character
        }
        
        // Calculate sell price (e.g., 80% of original price)
        $sellPrice = $property['price'] * 0.8;
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Add money to character
            $characterModel = new Character();
            $characterModel->addMoney($characterId, $sellPrice);
            
            // Update property ownership
            $stmt = $this->db->prepare("
                UPDATE properties
                SET character_id = NULL, 
                    purchase_date = NULL,
                    purchase_type = NULL
                WHERE id = :property_id
            ");
            $stmt->bindValue(':property_id', $propertyId);
            $stmt->execute();
            
            // Remove furniture items from the property
            $stmt = $this->db->prepare("
                DELETE FROM property_furniture
                WHERE property_id = :property_id
            ");
            $stmt->bindValue(':property_id', $propertyId);
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Get property furniture
     * 
     * @param int $propertyId Property ID
     * @return array Furniture items
     */
    public function getPropertyFurniture($propertyId)
    {
        $stmt = $this->db->prepare("
            SELECT pf.*, f.name, f.description, f.type, f.price
            FROM property_furniture pf
            JOIN furniture f ON pf.furniture_id = f.id
            WHERE pf.property_id = :property_id
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Add furniture to property
     * 
     * @param int $propertyId Property ID
     * @param int $furnitureId Furniture ID
     * @param int $characterId Owner character ID
     * @param array $position Position data (x, y, z coordinates)
     * @return bool Success status
     */
    public function addFurniture($propertyId, $furnitureId, $characterId, $position = [])
    {
        // Verify property ownership
        $stmt = $this->db->prepare("
            SELECT id FROM properties
            WHERE id = :property_id AND character_id = :character_id
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        if (!$stmt->fetch()) {
            return false; // Not owned by this character
        }
        
        // Get furniture price
        $stmt = $this->db->prepare("
            SELECT price FROM furniture
            WHERE id = :furniture_id
            LIMIT 1
        ");
        $stmt->bindValue(':furniture_id', $furnitureId);
        $stmt->execute();
        $furniture = $stmt->fetch();
        
        if (!$furniture) {
            return false; // Furniture not found
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Deduct money from character
            $characterModel = new Character();
            $success = $characterModel->removeMoney($characterId, $furniture['price']);
            
            if (!$success) {
                $this->db->rollBack();
                return false; // Not enough money
            }
            
            // Add furniture to property
            $stmt = $this->db->prepare("
                INSERT INTO property_furniture (
                    property_id, furniture_id, position_x, position_y, position_z,
                    rotation, added_at
                ) VALUES (
                    :property_id, :furniture_id, :position_x, :position_y, :position_z,
                    :rotation, :added_at
                )
            ");
            $stmt->bindValue(':property_id', $propertyId);
            $stmt->bindValue(':furniture_id', $furnitureId);
            $stmt->bindValue(':position_x', $position['x'] ?? 0);
            $stmt->bindValue(':position_y', $position['y'] ?? 0);
            $stmt->bindValue(':position_z', $position['z'] ?? 0);
            $stmt->bindValue(':rotation', $position['rotation'] ?? 0);
            $stmt->bindValue(':added_at', date('Y-m-d H:i:s'));
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Remove furniture from property
     * 
     * @param int $furnitureInstanceId Furniture instance ID
     * @param int $propertyId Property ID
     * @param int $characterId Owner character ID
     * @param bool $sellBack Whether to sell the furniture back
     * @return bool Success status
     */
    public function removeFurniture($furnitureInstanceId, $propertyId, $characterId, $sellBack = true)
    {
        // Verify property ownership
        $stmt = $this->db->prepare("
            SELECT id FROM properties
            WHERE id = :property_id AND character_id = :character_id
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        if (!$stmt->fetch()) {
            return false; // Not owned by this character
        }
        
        // Get furniture details
        $stmt = $this->db->prepare("
            SELECT pf.*, f.price
            FROM property_furniture pf
            JOIN furniture f ON pf.furniture_id = f.id
            WHERE pf.id = :id AND pf.property_id = :property_id
            LIMIT 1
        ");
        $stmt->bindValue(':id', $furnitureInstanceId);
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->execute();
        $furniture = $stmt->fetch();
        
        if (!$furniture) {
            return false; // Furniture not found
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Remove furniture from property
            $stmt = $this->db->prepare("
                DELETE FROM property_furniture
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $furnitureInstanceId);
            $stmt->execute();
            
            // If selling back, add money to character (e.g., 50% of original price)
            if ($sellBack) {
                $sellPrice = $furniture['price'] * 0.5;
                $characterModel = new Character();
                $characterModel->addMoney($characterId, $sellPrice);
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    /**
     * Update furniture position
     * 
     * @param int $furnitureInstanceId Furniture instance ID
     * @param int $propertyId Property ID
     * @param int $characterId Owner character ID
     * @param array $position New position data
     * @return bool Success status
     */
    public function updateFurniturePosition($furnitureInstanceId, $propertyId, $characterId, $position)
    {
        // Verify property ownership
        $stmt = $this->db->prepare("
            SELECT id FROM properties
            WHERE id = :property_id AND character_id = :character_id
            LIMIT 1
        ");
        $stmt->bindValue(':property_id', $propertyId);
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        if (!$stmt->fetch()) {
            return false; // Not owned by this character
        }
        
        // Update furniture position
        $stmt = $this->db->prepare("
            UPDATE property_furniture
            SET position_x = :position_x,
                position_y = :position_y,
                position_z = :position_z,
                rotation = :rotation
            WHERE id = :id AND property_id = :property_id
        ");
        $stmt->bindValue(':position_x', $position['x'] ?? 0);
        $stmt->bindValue(':position_y', $position['y'] ?? 0);
        $stmt->bindValue(':position_z', $position['z'] ?? 0);
        $stmt->bindValue(':rotation', $position['rotation'] ?? 0);
        $stmt->bindValue(':id', $furnitureInstanceId);
        $stmt->bindValue(':property_id', $propertyId);
        return $stmt->execute();
    }
} 