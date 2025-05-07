<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Property;
use App\Models\Character;

/**
 * Property Controller
 */
class PropertyController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // All property methods require authentication
        $this->requireAuth();
        
        // Load character model
        $this->characterModel = new Character();
        
        // Get current character
        $this->character = $this->characterModel->getByUserId($this->currentUser['id']);
        
        // Check if character exists
        if (!$this->character) {
            $this->redirect('character/create');
        }
    }
    
    /**
     * Property marketplace
     */
    public function index()
    {
        // Get property model
        $propertyModel = new Property();
        
        // Get filter parameters
        $type = $_GET['type'] ?? null;
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;
        
        // Prepare filters
        $filters = [];
        
        if ($type) {
            $filters['type'] = $type;
        }
        
        if ($minPrice !== null && is_numeric($minPrice)) {
            $filters['min_price'] = $minPrice;
        }
        
        if ($maxPrice !== null && is_numeric($maxPrice)) {
            $filters['max_price'] = $maxPrice;
        }
        
        // Get available properties
        $properties = $propertyModel->getAvailableProperties($filters);
        
        // Load view
        $this->view('property/index', [
            'properties' => $properties,
            'character' => $this->character,
            'filters' => [
                'type' => $type,
                'min_price' => $minPrice,
                'max_price' => $maxPrice
            ]
        ]);
    }
    
    /**
     * View property details
     * 
     * @param int $id Property ID
     */
    public function view($id)
    {
        // Get property model
        $propertyModel = new Property();
        
        // Get property details
        $property = $propertyModel->getPropertyDetails($id);
        
        // Check if property exists
        if (!$property) {
            $this->redirect('property');
        }
        
        // Load view
        $this->view('property/view', [
            'property' => $property,
            'character' => $this->character,
            'isOwner' => $property['character_id'] == $this->character['id']
        ]);
    }
    
    /**
     * Buy a property
     * 
     * @param int $id Property ID
     */
    public function buy($id)
    {
        // Check if request is POST
        if (!$this->isPost()) {
            $this->redirect('property/view/' . $id);
        }
        
        // Get property model
        $propertyModel = new Property();
        
        // Get property details
        $property = $propertyModel->getPropertyDetails($id);
        
        // Check if property exists and is available
        if (!$property || $property['character_id']) {
            $this->redirect('property');
        }
        
        // Get purchase type (buy or rent)
        $purchaseType = $_POST['purchase_type'] ?? 'buy';
        
        // Try to purchase property
        $success = $propertyModel->purchaseProperty($id, $this->character['id'], $purchaseType);
        
        // Check if purchase was successful
        if ($success) {
            // Redirect to property details
            $this->redirect('property/myproperties');
        } else {
            // Redirect back with error
            $_SESSION['property_error'] = 'Not enough money to purchase this property';
            $this->redirect('property/view/' . $id);
        }
    }
    
    /**
     * View my properties
     */
    public function myproperties()
    {
        // Get character's properties
        $propertyModel = new Property();
        $properties = $propertyModel->getProperties($this->character['id']);
        
        // Load view
        $this->view('property/myproperties', [
            'properties' => $properties,
            'character' => $this->character
        ]);
    }
    
    /**
     * Manage property
     * 
     * @param int $id Property ID
     */
    public function manage($id)
    {
        // Get property model
        $propertyModel = new Property();
        
        // Get property details
        $property = $propertyModel->getPropertyDetails($id);
        
        // Check if property exists and is owned by character
        if (!$property || $property['character_id'] != $this->character['id']) {
            $this->redirect('property/myproperties');
        }
        
        // Get property furniture
        $furniture = $propertyModel->getPropertyFurniture($id);
        
        // Load view
        $this->view('property/manage', [
            'property' => $property,
            'furniture' => $furniture,
            'character' => $this->character
        ]);
    }
    
    /**
     * Sell property
     * 
     * @param int $id Property ID
     */
    public function sell($id)
    {
        // Check if request is POST
        if (!$this->isPost()) {
            $this->redirect('property/manage/' . $id);
        }
        
        // Get property model
        $propertyModel = new Property();
        
        // Get property details
        $property = $propertyModel->getPropertyDetails($id);
        
        // Check if property exists and is owned by character
        if (!$property || $property['character_id'] != $this->character['id']) {
            $this->redirect('property/myproperties');
        }
        
        // Confirm sale
        $confirm = $_POST['confirm'] ?? '';
        
        if ($confirm !== 'yes') {
            $this->redirect('property/manage/' . $id);
        }
        
        // Try to sell property
        $success = $propertyModel->sellProperty($id, $this->character['id']);
        
        // Check if sale was successful
        if ($success) {
            // Redirect to property marketplace
            $this->redirect('property/myproperties');
        } else {
            // Redirect back with error
            $_SESSION['property_error'] = 'Error selling property';
            $this->redirect('property/manage/' . $id);
        }
    }
    
    /**
     * Furniture store
     */
    public function furniture()
    {
        // Get furniture categories
        $categories = $this->getFurnitureCategories();
        
        // Get selected category
        $category = $_GET['category'] ?? null;
        
        // Get furniture items for selected category
        $furniture = $this->getFurnitureItems($category);
        
        // Load view
        $this->view('property/furniture', [
            'categories' => $categories,
            'selectedCategory' => $category,
            'furniture' => $furniture,
            'character' => $this->character
        ]);
    }
    
    /**
     * Buy furniture
     * 
     * @param int $id Furniture ID
     */
    public function buyfurniture($id)
    {
        // Check if request is POST
        if (!$this->isPost()) {
            $this->redirect('property/furniture');
        }
        
        // Get property ID
        $propertyId = $_POST['property_id'] ?? 0;
        
        // Get property model
        $propertyModel = new Property();
        
        // Get property details
        $property = $propertyModel->getPropertyDetails($propertyId);
        
        // Check if property exists and is owned by character
        if (!$property || $property['character_id'] != $this->character['id']) {
            $this->redirect('property/furniture');
        }
        
        // Try to add furniture to property
        $position = [
            'x' => $_POST['position_x'] ?? 0,
            'y' => $_POST['position_y'] ?? 0,
            'z' => $_POST['position_z'] ?? 0,
            'rotation' => $_POST['rotation'] ?? 0
        ];
        
        $success = $propertyModel->addFurniture($propertyId, $id, $this->character['id'], $position);
        
        // Check if purchase was successful
        if ($success) {
            // Redirect to property management
            $this->redirect('property/manage/' . $propertyId);
        } else {
            // Redirect back with error
            $_SESSION['furniture_error'] = 'Not enough money to purchase this furniture';
            $this->redirect('property/furniture');
        }
    }
    
    /**
     * Remove furniture
     * 
     * @param int $id Furniture instance ID
     */
    public function removefurniture($id)
    {
        // Check if request is POST
        if (!$this->isPost()) {
            $this->redirect('property/myproperties');
        }
        
        // Get property ID
        $propertyId = $_POST['property_id'] ?? 0;
        
        // Get sell back option
        $sellBack = isset($_POST['sell_back']) && $_POST['sell_back'] === 'yes';
        
        // Get property model
        $propertyModel = new Property();
        
        // Try to remove furniture
        $success = $propertyModel->removeFurniture($id, $propertyId, $this->character['id'], $sellBack);
        
        // Redirect to property management
        $this->redirect('property/manage/' . $propertyId);
    }
    
    /**
     * Update furniture position
     * 
     * @param int $id Furniture instance ID
     */
    public function updatefurniture($id)
    {
        // Check if request is POST
        if (!$this->isPost()) {
            $this->redirect('property/myproperties');
        }
        
        // Get property ID
        $propertyId = $_POST['property_id'] ?? 0;
        
        // Get position data
        $position = [
            'x' => $_POST['position_x'] ?? 0,
            'y' => $_POST['position_y'] ?? 0,
            'z' => $_POST['position_z'] ?? 0,
            'rotation' => $_POST['rotation'] ?? 0
        ];
        
        // Get property model
        $propertyModel = new Property();
        
        // Try to update furniture position
        $success = $propertyModel->updateFurniturePosition($id, $propertyId, $this->character['id'], $position);
        
        // Redirect to property management
        $this->redirect('property/manage/' . $propertyId);
    }
    
    /**
     * Get furniture categories
     * 
     * @return array Categories
     */
    private function getFurnitureCategories()
    {
        return [
            'living_room' => 'Living Room',
            'bedroom' => 'Bedroom',
            'kitchen' => 'Kitchen',
            'bathroom' => 'Bathroom',
            'office' => 'Office',
            'outdoor' => 'Outdoor',
            'decoration' => 'Decoration'
        ];
    }
    
    /**
     * Get furniture items for a category
     * 
     * @param string $category Category
     * @return array Furniture items
     */
    private function getFurnitureItems($category = null)
    {
        // Query database for furniture items
        $sql = "SELECT * FROM furniture";
        $params = [];
        
        if ($category) {
            $sql .= " WHERE type = :category";
            $params[':category'] = $category;
        }
        
        $sql .= " ORDER BY price ASC";
        
        $stmt = $this->characterModel->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
} 