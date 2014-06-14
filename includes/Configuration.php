<?php

/**
 * Configuration class for application. Meant to be used in static mean.
 * Instantiation is not allowed
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @since 11 May, 2014
 */
final class Configuration {

    // All Configuration keys
    const DB_HOST = 'db_host';
    const DB_USER = 'db_user';
    const DB_PASS = 'db_pass';
    const DB_NAME = 'db_name';
    const MYSQL_DSN = 'mysql_dsn';
    const LOG_FILE = 'log_file';
    const LOG_LEVEL = 'log_level';
    const CODE_BASE_DIR = 'code_base_dir';
    const CODE_EDITOR_THEME = 'code_editor_theme';

    // Restricted contructor for singleton pattern
    private function __construct() {}

    // Configuration map
    private static $config = array(
        self::DB_HOST => 'localhost',
        self::DB_USER => 'root',
        self::DB_PASS => 'cvbert',
        self::DB_NAME => 'codeme',
        self::LOG_FILE => 'data/logs/access.log',
        self::LOG_LEVEL => 1,
        self::CODE_BASE_DIR => 'data/codes/',
        self::CODE_EDITOR_THEME => 'chrome',
    );

    /**
     * Configuration getter by key.
     * This method is used to get config by outside world
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
