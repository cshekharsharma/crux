<?php

class UsersController extends AbstractController {

    const MODULE_KEY = "users";
    
    public function run(Resource $resource) {

    }

    public function getUserList() {
        $userList = array();
        $query = "SELECT ".Users_DBTable::USER_ID.", ";
        $query .= Users_DBTable::USER_NAME." FROM ";
        $query .= Users_DBTable::DB_TABLE_NAME . " WHERE ";
        $query .= Users_DBTable::IS_DELETED . "= 0 ORDER BY ";
        $query .= Users_DBTable::USER_NAME;
        $resultSet = DBManager::executeQuery($query, array(), true);
        return $resultSet;
    }
}