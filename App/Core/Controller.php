<?php
namespace App\Core;

/**
 * Base Controller Class
 * All controllers should extend this class
 */
abstract class Controller
{
    /**
     * Reference to current user
     * @var \App\Models\User|null
     */
    protected $currentUser = null;
    
    /**
     * Controller constructor
     */
    public function __construct()
    {
        // Check if user is logged in
        $this->checkAuth();
    }
    
    /**
     * Check if user is authenticated
     */
    protected function checkAuth()
    {
        if (isset($_SESSION['user_id'])) {
            // Load the user model if not already loaded
            if (!class_exists('\\App\\Models\\User')) {
                require_once BASE_PATH . '/App/Models/User.php';
            }
            
            // Get user by ID
            $userModel = new \App\Models\User();
            $this->currentUser = $userModel->getById($_SESSION['user_id']);
        }
    }
    
    /**
     * Load a view file
     * 
     * @param string $view View file to load
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function view($view, $data = [])
    {
        // Add current user to all views
        $data['currentUser'] = $this->currentUser;
        
        // Add Language class to all views
        $data['lang'] = \App\Core\Language::class;
        
        // Check if view file exists
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // Extract data to make variables available in view
            extract($data);
            
            // Include view file
            require_once $viewFile;
        } else {
            die("View file '$viewFile' not found");
        }
    }
    
    /**
     * Redirect to another URL
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect($url)
    {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Check if request is POST
     * 
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if user is authenticated
     * Redirect to login page if not
     * 
     * @return void
     */
    protected function requireAuth()
    {
        if (!$this->currentUser) {
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Load a language file
     * 
     * @param string $file Language file to load
     * @return bool Success status
     */
    protected function loadLanguage($file)
    {
        return \App\Core\Language::load($file);
    }
    
    /**
     * Get a language string
     * 
     * @param string $key String key
     * @param array $params Optional parameters for placeholders
     * @return string Language string
     */
    protected function lang($key, $params = [])
    {
        return \App\Core\Language::get($key, $params);
    }
} 