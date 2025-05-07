<?php
namespace App\Models;

use App\Core\Model;

/**
 * Character Model
 */
class Character extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'characters';
    
    /**
     * Create a new character
     * 
     * @param int $userId User ID
     * @param array $data Character data
     * @return int|false Character ID or false on failure
     */
    public function createCharacter($userId, $data)
    {
        // Merge user ID with character data
        $characterData = array_merge([
            'user_id' => $userId,
            'created_at' => date('Y-m-d H:i:s'),
            'last_activity' => date('Y-m-d H:i:s'),
            'money' => STARTING_MONEY,
            'health' => 100,
            'energy' => 100,
            'hunger' => 0,
            'thirst' => 0,
            'happiness' => 80,
            'intelligence' => 50,
            'strength' => 50,
            'charisma' => 50,
            'age' => 18,
            'status' => 'active'
        ], $data);
        
        // Create character
        return $this->create($characterData);
    }
    
    /**
     * Get character by user ID
     * 
     * @param int $userId User ID
     * @return array|false Character data or false if not found
     */
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1");
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Update character stats
     * 
     * @param int $characterId Character ID
     * @param array $stats Stats to update
     * @return bool Success status
     */
    public function updateStats($characterId, $stats)
    {
        // Ensure stats are within valid ranges
        if (isset($stats['health'])) {
            $stats['health'] = max(0, min(100, $stats['health']));
        }
        
        if (isset($stats['energy'])) {
            $stats['energy'] = max(0, min(100, $stats['energy']));
        }
        
        if (isset($stats['hunger'])) {
            $stats['hunger'] = max(0, min(100, $stats['hunger']));
        }
        
        if (isset($stats['thirst'])) {
            $stats['thirst'] = max(0, min(100, $stats['thirst']));
        }
        
        if (isset($stats['happiness'])) {
            $stats['happiness'] = max(0, min(100, $stats['happiness']));
        }
        
        // Add last activity timestamp
        $stats['last_activity'] = date('Y-m-d H:i:s');
        
        // Update character
        return $this->update($characterId, $stats);
    }
    
    /**
     * Get character inventory
     * 
     * @param int $characterId Character ID
     * @return array Inventory items
     */
    public function getInventory($characterId)
    {
        $stmt = $this->db->prepare("
            SELECT i.*, it.name, it.description, it.type, it.effects
            FROM inventory i
            JOIN items it ON i.item_id = it.id
            WHERE i.character_id = :character_id
        ");
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Add item to character inventory
     * 
     * @param int $characterId Character ID
     * @param int $itemId Item ID
     * @param int $quantity Quantity
     * @return bool Success status
     */
    public function addInventoryItem($characterId, $itemId, $quantity = 1)
    {
        // Check if item already exists in inventory
        $stmt = $this->db->prepare("
            SELECT id, quantity FROM inventory
            WHERE character_id = :character_id AND item_id = :item_id
            LIMIT 1
        ");
        $stmt->bindValue(':character_id', $characterId);
        $stmt->bindValue(':item_id', $itemId);
        $stmt->execute();
        $existingItem = $stmt->fetch();
        
        if ($existingItem) {
            // Update quantity
            $stmt = $this->db->prepare("
                UPDATE inventory
                SET quantity = quantity + :quantity
                WHERE id = :id
            ");
            $stmt->bindValue(':quantity', $quantity, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $existingItem['id'], \PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            // Add new item
            $stmt = $this->db->prepare("
                INSERT INTO inventory (character_id, item_id, quantity)
                VALUES (:character_id, :item_id, :quantity)
            ");
            $stmt->bindValue(':character_id', $characterId, \PDO::PARAM_INT);
            $stmt->bindValue(':item_id', $itemId, \PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, \PDO::PARAM_INT);
            return $stmt->execute();
        }
    }
    
    /**
     * Remove item from character inventory
     * 
     * @param int $characterId Character ID
     * @param int $itemId Item ID
     * @param int $quantity Quantity
     * @return bool Success status
     */
    public function removeInventoryItem($characterId, $itemId, $quantity = 1)
    {
        // Check if item exists in inventory
        $stmt = $this->db->prepare("
            SELECT id, quantity FROM inventory
            WHERE character_id = :character_id AND item_id = :item_id
            LIMIT 1
        ");
        $stmt->bindValue(':character_id', $characterId);
        $stmt->bindValue(':item_id', $itemId);
        $stmt->execute();
        $existingItem = $stmt->fetch();
        
        if (!$existingItem) {
            return false;
        }
        
        if ($existingItem['quantity'] <= $quantity) {
            // Remove item completely
            $stmt = $this->db->prepare("
                DELETE FROM inventory
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $existingItem['id'], \PDO::PARAM_INT);
        } else {
            // Reduce quantity
            $stmt = $this->db->prepare("
                UPDATE inventory
                SET quantity = quantity - :quantity
                WHERE id = :id
            ");
            $stmt->bindValue(':quantity', $quantity, \PDO::PARAM_INT);
            $stmt->bindValue(':id', $existingItem['id'], \PDO::PARAM_INT);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Get character's properties
     * 
     * @param int $characterId Character ID
     * @return array Properties
     */
    public function getProperties($characterId)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, pt.name, pt.description, pt.price
            FROM properties p
            JOIN property_types pt ON p.property_type_id = pt.id
            WHERE p.character_id = :character_id
        ");
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Add money to character
     * 
     * @param int $characterId Character ID
     * @param float $amount Amount to add
     * @return bool Success status
     */
    public function addMoney($characterId, $amount)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET money = money + :amount
            WHERE id = :character_id
        ");
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':character_id', $characterId);
        return $stmt->execute();
    }
    
    /**
     * Remove money from character
     * 
     * @param int $characterId Character ID
     * @param float $amount Amount to remove
     * @return bool Success status
     */
    public function removeMoney($characterId, $amount)
    {
        // Get current money
        $stmt = $this->db->prepare("
            SELECT money FROM {$this->table}
            WHERE id = :character_id
        ");
        $stmt->bindValue(':character_id', $characterId);
        $stmt->execute();
        $money = $stmt->fetchColumn();
        
        // Check if character has enough money
        if ($money < $amount) {
            return false;
        }
        
        // Remove money
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET money = money - :amount
            WHERE id = :character_id
        ");
        $stmt->bindValue(':amount', $amount);
        $stmt->bindValue(':character_id', $characterId);
        return $stmt->execute();
    }
} 