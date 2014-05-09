<?php

/**
 * Configuration class for application. Meant to be used in static mean.
 * Instantiation is not allowed
 *  
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class Configuration {

    const DB_HOST = 'db_host';
    const DB_USER = 'db_user';
    const DB_PASS = 'db_pass';
    const DB_NAME = 'db_name';
    const MYSQL_DSN = 'mysql_dsn';
    const LOG_FILE = 'log_file';
    const LOG_LEVEL = 'log_level';
    const CODE_BASE_DIR = 'code_base_dir';
    const CODE_EDITOR_THEME = 'code_editor_theme';
    
    private function __construct() {}

    private static $config = array(
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_pass' => 'cvbert',
        'db_name' => 'codeme_test',
        'mysql_dsn' => 'mysql:host=localhost;dbname=codeme',
        'log_file' => 'data/logs/access.log',
        'log_level' => 1,
        'code_base_dir' => 'data/test_codes/',
        'code_editor_theme' => 'chrome'
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
