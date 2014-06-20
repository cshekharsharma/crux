<?php
/**
 * Controller class for Language module
 * 
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class LanguageController extends AbstractController {
    
    const MODULE_KEY = 'language';
    
    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        
    }

    /**
     * Returns list of all active languages in system
     * 
     * @return array
     */
    public function getLanguageList() {
        $languageList = array();
        $query = "SELECT id, name FROM ";
        $query .= Language_DBTable::DB_TABLE_NAME . " WHERE ";
        $query .= Language_DBTable::IS_DELETED . "= 0 ORDER BY ";
        $query .= Language_DBTable::LANGUAGE_NAME;
        $resultSet = DBManager::executeQuery($query, array(), true);
        foreach ($resultSet as $row) {
            $languageList[$row[Language_DBTable::LANGUAGE_ID]] = $row;
        }
        return $resultSet;
    }
}