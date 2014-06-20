<?php
/**
 * Controller class for Category module
 *
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package controllers
 * @since May 20, 2014
 */
class CategoryController extends AbstractController {

    const MODULE_KEY = 'category';

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
    }

    /**
     * Get list of all active categories in database
     * 
     * @return boolean $resultSet
     */
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