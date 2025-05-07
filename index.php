<?php
/**
 * HelixRP - Fantasy Life Simulation MMORPG
 * Main Entry Point
 */

// Define base path
define('BASE_PATH', __DIR__);

// Error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize core application
$app = new App\Core\Application();

// Process the request
$app->processRequest(); 