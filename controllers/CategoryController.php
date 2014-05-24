<?php

class CategoryController extends AbstractController {
    
    const MODULE_KEY = 'category';
    
    public function run(Resource $resource) {
        
    }
    
    public function getCategoryList() {
        $categoryList = array();
        $query = "SELECT id, name FROM ";
        $query .= Category_DBTable::DB_TABLE_NAME . " WHERE ";
        $query .= Category_DBTable::IS_DELETED . "= 0 ORDER BY ";
        $query .= Category_DBTable::CATEGORY_NAME;
        $resultSet = DBManager::executeQuery($query, array(), true);
        foreach ($resultSet as $row) {
            $categoryList[$row[Category_DBTable::CATEGORY_ID]] = $row;
        }
        return $resultSet;
    }
}