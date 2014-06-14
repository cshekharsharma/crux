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

    /**
     * Creates new user in database with given user name and password
     *
     * @param string $userName
     * @param string $userPass
     */
    public function createUser($userName, $userPass) {
        if (empty($userName) || empty($userPass)) {
            return Response::getResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_USERNAME_OR_PASSWORD_EMPTY);
        }

        if (!$this->isUserExist($userName)) {
            $auth = new AuthController();
            $userHash = $auth->getPasswordHash($userPass);
            $userInfo = array($userName, $userHash);
            $query = "INSERT INTO ".Users_DBTable::DB_TABLE_NAME." VALUES('',?,?,'','',0,NOW(),NOW(),1,0);";
            if (DBManager::executeQuery($query, $userInfo)) {
                return Response::getResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_USER_CREATED);
            } else {
                return Response::getResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_SOMETHING_WENT_WRONG);
            }
        } else {
            return Response::getResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_USER_ALREADY_EXISTS);
        }
    }

    /**
     * Checks if user with given name exists in database or not
     *
     * @param string $userName
     * @return boolean
     */
    public function isUserExist($userName) {
        $query = 'SELECT COUNT(*) C FROM '.Users_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= Users_DBTable::USER_NAME.' = ? ';
        $result = DBManager::executeQuery($query, array($userName), true);
        if (is_array($result)) {
            $row = current($result);
            return $row['C'];
        } else {
            return false;
        }
    }
}