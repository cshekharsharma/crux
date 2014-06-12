<?php

class IndexModel extends AbstractModel {
    
    public function getProgramList($lang = false, $category = false) {
        $programList = array();
        $bindParams = array();
        if (empty($lang) && empty($category)) {
            IndexController::$isHomePage = true;
        }
        $pidCol = ProgramDetails_DBTable::PROGRAM_ID;
        $langCol = ProgramDetails_DBTable::FK_LANGUAGE_ID;
        $cateCol = ProgramDetails_DBTable::FK_CATEGORY_ID;
        $query = 'SELECT '.ProgramDetails_DBTable::DB_TABLE_NAME.'.*,'.
            Users_DBTable::DB_TABLE_NAME.'.'.Users_DBTable::USER_NAME.' AS created_by,'.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_NAME.' AS category_name,'.
            Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_NAME.' AS language_name FROM '.
            ProgramDetails_DBTable::DB_TABLE_NAME.' INNER JOIN '.Category_DBTable::DB_TABLE_NAME.' ON '.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID.' INNER JOIN '.
            Language_DBTable::DB_TABLE_NAME.' ON '.Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID.' INNER JOIN '.
            Users_DBTable::DB_TABLE_NAME.' ON '.Users_DBTable::DB_TABLE_NAME.'.'.Users_DBTable::USER_ID.' = '.
            ProgramDetails_DBTable::FK_CREATED_BY.' WHERE ';
        if (!empty($lang)) {
            $bindParams[] = $lang;
            $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID."=? AND ";
            if (!empty($category)) {
                $bindParams[] = $category;
                $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID."=? AND ";
            }
        }
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::IS_DELETED."= '0'";
        $resultSet = DBManager::executeQuery($query, $bindParams, true);
        return $resultSet;
    }
    
}