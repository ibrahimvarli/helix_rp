<?php
namespace App\Core;

/**
 * Main Application Class
 */
class Application
{
    private $controller;
    private $action;
    private $params = [];
    private $controllerInstance;

    /**
     * Initialize the application
     */
    public function __construct()
    {
        // Start session
        $this->initSession();
        
        // Initialize language system
        $this->initLanguage();
        
        // Parse URL and set controller, action and params
        $this->parseUrl();
    }

    /**
     * Initialize session
     */
    private function initSession()
    {
        session_name(SESSION_NAME);
        session_set_cookie_params(SESSION_LIFETIME);
        session_start();
    }
    
    /**
     * Initialize language system
     */
    private function initLanguage()
    {
        // Get language from session or use default
        $lang = $_SESSION['language'] ?? APP_LANG;
        
        // Initialize language
        Language::init($lang);
    }

    /**
     * Parse the URL into controller, action and parameters
     */
    private function parseUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        // Set controller
        $this->controller = !empty($url[0]) ? $url[0] : DEFAULT_CONTROLLER;
        
        // Set action
        $this->action = isset($url[1]) ? $url[1] : DEFAULT_ACTION;
        
        // Set params
        unset($url[0], $url[1]);
        $this->params = !empty($url) ? array_values($url) : [];
    }

    /**
     * Process the request and call the appropriate controller action
     */
    public function processRequest()
    {
        // Format controller class name
        $controllerClass = 'App\\Controllers\\' . ucfirst($this->controller) . 'Controller';
        
        // Check if controller exists
        if (!class_exists($controllerClass)) {
            $this->loadErrorPage(404, "Controller '$controllerClass' not found");
            return;
        }
        
        // Create controller instance
        $this->controllerInstance = new $controllerClass();
        
        // Check if action exists
        if (!method_exists($this->controllerInstance, $this->action)) {
            $this->loadErrorPage(404, "Action '{$this->action}' not found in controller '{$controllerClass}'");
            return;
        }
        
        // Call the action with parameters
        call_user_func_array([$this->controllerInstance, $this->action], $this->params);
    }

    /**
     * Load error page
     * 
     * @param int $code HTTP status code
     * @param string $message Error message
     */
    private function loadErrorPage($code, $message)
    {
        header("HTTP/1.0 $code");
        
        $errorController = new \App\Controllers\ErrorController();
        $errorController->index($code, $message);
    }
} 