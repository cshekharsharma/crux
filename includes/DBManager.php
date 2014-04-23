<?php

/**
 * Database Manager for application
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @uses PDO
 */
class DBManager {

    private function __construct() {}
    
    private static $instance;
    
    public static function getInstance() {
        if (!self::$instance instanceof PDO) {
            $dsn  = Configuration::get('MYSQL_DSN');
            $host = Configuration::get('DB_HOST');
            $user = Configuration::get('DB_USER');
            $pass = Configuration::get('DB_PASS');
            $name = Configuration::get('DB_NAME');
            try {
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                Logger::getLogger()->LogFatal($e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function executeQuery($query, $bindParams = false, $shouldReturn = false) {
        try {
            $instance = self::getInstance();
            $instance->beginTransaction();
            $stmt = $instance->prepare($query);
            $querySuccess = false;
            if (!empty($bindParams)) {
                $querySuccess = $stmt->execute($bindParams);
            } else {
                $querySuccess = $stmt->execute();
            }

            if ($querySuccess) {
                if ($shouldReturn) {
                    $resultSet = array();
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $resultSet[] = $row;
                    }
                } else {
                    $resultSet = true;
                }
            } else {
                $resultSet = false;
            }

            $instance->commit();
            return $resultSet;

        } catch (PDOException $e) {
            $instance->rollBack();
            Logger::getLogger()->LogFatal($e->getMessage());
        }
    }
}    