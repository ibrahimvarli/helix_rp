<?php
/**
 * HelixRP - Application Configuration
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'helixrp');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application settings
define('APP_NAME', 'HelixRP');
define('APP_URL', 'http://localhost/helixrp');
define('DEFAULT_CONTROLLER', 'home');
define('DEFAULT_ACTION', 'index');
define('APP_LANG', 'tr'); // Dil ayarı (tr: Türkçe, en: İngilizce)

// Session settings
define('SESSION_NAME', 'helixrp_session');
define('SESSION_LIFETIME', 86400); // 24 hours

// Security settings
define('HASH_COST', 12); // For password hashing
define('AUTH_TOKEN_LIFETIME', 3600); // 1 hour

// Game settings
define('GAME_TIME_RATIO', 24); // 1 real hour = 24 game hours
define('MAX_CHARACTER_AGE', 100);
define('STARTING_MONEY', 1000);

// Template settings
define('VIEWS_PATH', BASE_PATH . '/App/Views');
define('ASSETS_PATH', APP_URL . '/public');
define('LANG_PATH', BASE_PATH . '/App/Lang'); 