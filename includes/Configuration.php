<?php

/**
 * Configuration class for application. Meant to be used in static mean.
 * Instantiation is not allowed
 *  
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class Configuration {

    private function __construct() {}

    private static $config = array(
        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_PASS' => 'cvbert',
        'DB_NAME' => 'codeme',
        'MYSQL_DSN' => 'mysql:host=localhost;dbname=codeme',
        'LOGGER_FILE' => 'data/logs/access.log',
        'LOGGER_LEVEL' => 1,
        'CODE_BASE_DIR' => 'data/codes/',
        'CODE_EDITOR_THEME' => 'chrome'
    );

    /**
     * Configuration getter by key
     * 
     * @param sting $key
     * @return string | boolean
     */
    public static function get($key) {
        if (!empty(self::$config[$key])) {
            return self::$config[$key];
        } else {
            Logger::getLogger()->LogWarn("Trying to get undefined configuration <$key>");
            return false;
        }
    }
}
