<?php

class ExplorerModel extends AbstractModel {
    
    public function getSourceDetails($lang, $category, $pid) {
        $query = 'SELECT '.ProgramDetails_DBTable::DB_TABLE_NAME.'.*,'.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_NAME.' AS category_name,'.
            Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_NAME.' AS language_name FROM '.
            ProgramDetails_DBTable::DB_TABLE_NAME.' INNER JOIN '.Category_DBTable::DB_TABLE_NAME.' ON '.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID.' INNER JOIN '.
            Language_DBTable::DB_TABLE_NAME.' ON '.Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID.' WHERE '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::PROGRAM_ID."=? AND ".
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID."=? AND ".
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID."=? AND ".
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::IS_DELETED."= '0'";
        $bindParams = array($pid, $lang, $category);
        $resultSet = DBManager::executeQuery($query, $bindParams, true);
        return current($resultSet);
    }
}