<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Character;
use App\Models\User;

/**
 * Character Controller
 */
class CharacterController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        // Load language files
        $this->loadLanguage('auth');
        
        // Check if user is authenticated
        $this->requireAuth();
    }
    
    /**
     * Show character creation page
     */
    public function create()
    {
        // Check if user already has a character
        $characterModel = new Character();
        $character = $characterModel->getByUserId($this->currentUser['id']);
        
        if ($character) {
            // User already has a character, redirect to character page
            $this->redirect('character/view');
        }
        
        // Show character creation form
        if ($this->isPost()) {
            // Process form submission
            $name = $_POST['name'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $race = $_POST['race'] ?? '';
            $appearance = $_POST['appearance'] ?? '';
            
            // Validate input
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = $this->lang('character_name') . ' ' . $this->lang('field_required');
            } elseif (strlen($name) < 3) {
                $errors['name'] = $this->lang('character_name') . ' en az 3 karakter olmalıdır';
            } elseif (strlen($name) > 20) {
                $errors['name'] = $this->lang('character_name') . ' en fazla 20 karakter olmalıdır';
            }
            
            if (empty($gender)) {
                $errors['gender'] = $this->lang('character_gender') . ' ' . $this->lang('field_required');
            }
            
            if (empty($race)) {
                $errors['race'] = $this->lang('character_race') . ' ' . $this->lang('field_required');
            }
            
            // If no errors, create character
            if (empty($errors)) {
                $characterData = [
                    'name' => $name,
                    'gender' => $gender,
                    'race' => $race,
                    'appearance' => $appearance
                ];
                
                $success = $characterModel->createCharacter($this->currentUser['id'], $characterData);
                
                if ($success) {
                    // Set success message
                    $_SESSION['success'] = 'Karakteriniz başarıyla oluşturuldu!';
                    
                    // Redirect to character view
                    $this->redirect('game');
                } else {
                    $errors['create'] = 'Karakter oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.';
                }
            }
            
            // If there are errors, show form with errors
            $this->view('character/create', [
                'errors' => $errors,
                'name' => $name,
                'gender' => $gender,
                'race' => $race,
                'appearance' => $appearance
            ]);
        } else {
            // Show empty form
            $this->view('character/create');
        }
    }
    
    /**
     * Show character details
     */
    public function view()
    {
        return $this->showCharacter();
    }
    
    /**
     * Show character details (actual implementation)
     */
    public function showCharacter()
    {
        // Get character
        $characterModel = new Character();
        $character = $characterModel->getByUserId($this->currentUser['id']);
        
        if (!$character) {
            // User does not have a character, redirect to character creation
            $this->redirect('character/create');
        }
        
        // Get character inventory
        $inventory = $characterModel->getInventory($character['id']);
        
        // Get character properties
        $properties = $characterModel->getProperties($character['id']);
        
        // Show character details
        $this->view('character/view', [
            'character' => $character,
            'inventory' => $inventory,
            'properties' => $properties
        ]);
    }
    
    /**
     * Update character stats
     */
    public function update()
    {
        // Only allow POST requests
        if (!$this->isPost()) {
            $this->redirect('character/view');
        }
        
        // Get character
        $characterModel = new Character();
        $character = $characterModel->getByUserId($this->currentUser['id']);
        
        if (!$character) {
            // User does not have a character, redirect to character creation
            $this->redirect('character/create');
        }
        
        // Get stats to update
        $stats = [
            'health' => $_POST['health'] ?? $character['health'],
            'energy' => $_POST['energy'] ?? $character['energy'],
            'hunger' => $_POST['hunger'] ?? $character['hunger'],
            'thirst' => $_POST['thirst'] ?? $character['thirst'],
            'happiness' => $_POST['happiness'] ?? $character['happiness']
        ];
        
        // Update character stats
        $success = $characterModel->updateStats($character['id'], $stats);
        
        if ($success) {
            $_SESSION['success'] = 'Karakter istatistikleri güncellendi';
        } else {
            $_SESSION['error'] = 'Karakter istatistikleri güncellenirken bir hata oluştu';
        }
        
        // Redirect back to character view
        $this->redirect('character/view');
    }
    
    /**
     * Get available races
     */
    private function getRaces()
    {
        return [
            'human' => 'İnsan',
            'elf' => 'Elf',
            'dwarf' => 'Cüce',
            'orc' => 'Ork',
            'halfling' => 'Halfling',
            'gnome' => 'Cüce (Gnome)',
            'dragonborn' => 'Ejder Doğumlu',
            'tiefling' => 'Tiefling'
        ];
    }
} 