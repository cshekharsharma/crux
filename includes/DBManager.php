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

    /**
     * Gives PDO database instance. Singleton implementation
     *
     * @throws PDOException
     * @return PDO
     */
    public static function getInstance() {
        if (!self::$instance instanceof PDO) {
            $dsn  = self::getMysqlDSN();
            $host = Configuration::get(Configuration::DB_HOST);
            $user = Configuration::get(Configuration::DB_USER);
            $pass = Configuration::get(Configuration::DB_PASS);
            $name = Configuration::get(Configuration::DB_NAME);
            try {
                Logger::getLogger()->LogDebug('New DB Instance created');
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                Logger::getLogger()->LogFatal($e->getMessage());
            }
        }
        return self::$instance;
    }
    
    /**
     * Database connection string provider
     * 
     * @return string
     */
    private static function getMysqlDSN() {
        $DSN = 'mysql:host='.
        Configuration::get(Configuration::DB_HOST) . ';dbname=' .
        Configuration::get(Configuration::DB_NAME);
        return $DSN;
    }
    

    /**
     * Executes given query using PDO prepared statements.
     * returns query output if shouldReturn is true
     *
     * @param string $query
     * @param array|false $bindParams
     * @param boolean $shouldReturn
     * @return boolean
     */
    public static function executeQuery($query, $bindParams = false, $shouldReturn = false) {
        try {
            $instance = self::getInstance();
            $instance->beginTransaction();
            Logger::getLogger()->LogDebug('Executing Query: '.$query);
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

    /**
     * Wrapper method for DB update operation
     *
     * @param string $table
     * @param array $columns
     * @param array $bind
     * @param array $where [Optional]
     * @return boolean
     */
    public static function update($table, array $columns, array $bind, array $where = array()) {
        $set = array();
        foreach ($columns as $name => $value) {
            $set[] = "`$name` = '$value'";
        }
        $query = "UPDATE `$table` SET ";
        $query .= implode(',', $set);
        if (!empty($where)) {
            $query .= ' WHERE ';
            foreach ($where as $name => $value) {
                $set[] = "$name = '$value'";
            }
            $query .= implode(' AND ', $set);
        }
        return DBManager::executeQuery($query, $bind);
    }
    
    /**
     * Wrapper method for DB insert operation
     *
     * @param string $table
     * @param array $columns
     * @param array $bind [Optional]
     * @return boolean
     */
    public static function insert($table, array $columns, array $bind = array()) {
        $keyStr = implode(',', array_keys($columns));
        $valStr = implode("','", $columns);
        $query = "INSERT INTO `$table` ($keyStr) VALUE($valStr);";
        return DBManager::executeQuery($query, $bind);
    }
}

