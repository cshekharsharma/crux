<?php

class LanguageController extends AbstractController {
    
    const MODULE_KEY = 'language';
    
    public function run(Resource $resource) {
        
    }
    
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