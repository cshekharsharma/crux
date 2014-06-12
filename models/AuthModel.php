<?php 

class AuthModel extends AbstractModel {
    
    public function updateUserPassword($userDetails, $newPasswordHash) {
        $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ".
            Users_DBTable::USER_HASH." = '".$newPasswordHash."' WHERE ".
            Users_DBTable::USER_ID." = '".$userDetails[Users_DBTable::USER_ID]."'";
        
        return DBManager::executeQuery($query);
    }
    
    public function updateUserLogout($userId = null) {
        if (empty($userId)) {
            $userDetail = Session::get(Session::SESS_USER_DETAILS);
            $userId = $userDetail[Users_DBTable::USER_ID];
        }
        $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ";
        $query .= Users_DBTable::SESSION_ID. "='', ";
        $query .= Users_DBTable::IP_ADDRESS. "='', ";
        $query .= Users_DBTable::IS_LOGGED_IN." = '0' WHERE ";
        $query .= Users_DBTable::USER_ID." = '".$userId."'";
        return DBManager::executeQuery($query);
    }
    
    public function updateUserLogin($userDetails) {
        $sessionId = Session::getSessionId();
        $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ";
        $query .= Users_DBTable::SESSION_ID. "='" . $sessionId . "', ";
        $query .= Users_DBTable::IP_ADDRESS. "='".$userDetails[Users_DBTable::IP_ADDRESS]."', ";
        $query .= Users_DBTable::IS_LOGGED_IN." = '1', ";
        $query .= Users_DBTable::LAST_LOGIN." = '".date('Y-m-d H:i:s')."' WHERE ";
        $query .= Users_DBTable::USER_ID." = '".$userDetails[Users_DBTable::USER_ID]."'";
        return DBManager::executeQuery($query);
    }
}