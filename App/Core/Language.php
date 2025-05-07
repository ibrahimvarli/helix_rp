<?php
namespace App\Core;

/**
 * Language Class
 * Manages language strings for the application
 */
class Language
{
    /**
     * Current language
     * @var string
     */
    private static $lang;
    
    /**
     * Loaded language files
     * @var array
     */
    private static $loadedFiles = [];
    
    /**
     * Language strings
     * @var array
     */
    private static $strings = [];
    
    /**
     * Initialize the language system
     * 
     * @param string $lang Language code
     * @return void
     */
    public static function init($lang = null)
    {
        // Set default language from config if not specified
        self::$lang = $lang ?? APP_LANG ?? 'en';
        
        // Load general language file
        self::load('general');
    }
    
    /**
     * Load a language file
     * 
     * @param string $file File name without extension
     * @return bool Success status
     */
    public static function load($file)
    {
        // Check if file is already loaded
        if (isset(self::$loadedFiles[$file])) {
            return true;
        }
        
        // Build file path
        $filePath = LANG_PATH . '/' . self::$lang . '/' . $file . '.php';
        
        // Check if file exists
        if (!file_exists($filePath)) {
            // Try fallback to English
            $filePath = LANG_PATH . '/en/' . $file . '.php';
            if (!file_exists($filePath)) {
                return false;
            }
        }
        
        // Load language strings
        $strings = include $filePath;
        
        if (is_array($strings)) {
            // Merge with existing strings
            self::$strings = array_merge(self::$strings, $strings);
            self::$loadedFiles[$file] = true;
            return true;
        }
        
        return false;
    }
    
    /**
     * Get a language string
     * 
     * @param string $key String key
     * @param array $params Optional parameters for placeholders
     * @return string Language string or key if not found
     */
    public static function get($key, $params = [])
    {
        // Check if string exists
        if (isset(self::$strings[$key])) {
            $string = self::$strings[$key];
            
            // Replace parameters
            if (!empty($params)) {
                foreach ($params as $param => $value) {
                    $string = str_replace(':' . $param, $value, $string);
                }
            }
            
            return $string;
        }
        
        return $key;
    }
    
    /**
     * Get current language
     * 
     * @return string Current language code
     */
    public static function getCurrentLang()
    {
        return self::$lang;
    }
    
    /**
     * Set current language
     * 
     * @param string $lang Language code
     * @return void
     */
    public static function setLang($lang)
    {
        self::$lang = $lang;
        self::$loadedFiles = [];
        self::$strings = [];
        
        // Reload general language file
        self::load('general');
    }
} 