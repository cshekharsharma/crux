<?php

class AuthController extends BaseController {

    const MODULE_KEY = 'auth';

    const AUTH_ACTION = "AUTH_ACTION";
    const AUTH_LOGIN_KEY = "AUTH_LOGIN";
    const LOGIN_ACTION_NAME = "login-action-name";
    const LOGIN_ACTION_VALUE = "login_byajax_AXZFJTBDSSID";
    const CHPWD_ACTION_NAME = "chpwd-action-name";
    const CHPWD_ACTION_VALUE = "chpwd-byajax_LKNSBNSJDXXD";
    
    const SESSION_COOKIE_LIFETIME = 1209600; // 2 Weeks

    private static $AUTH_USER_ID_IDENTIFIER = "auth_user_id";
    private static $SESSION_SITE_IDENTIFIER = "codeMe_session_identifier";

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        $this->redirectAuthRequest($uriParams, $formParams);
    }

    private function redirectAuthRequest ($uriParams, $formParams) {
        $authAction = $uriParams[self::AUTH_ACTION];
        if (empty($authAction) || $authAction === Constants::AUTH_LOGIN_URI_KEY) {
            $formKey = $formParams[self::LOGIN_ACTION_NAME];
            if (!self::isLoggedIn() && ($formKey === self::LOGIN_ACTION_VALUE)) {
                $this->authenticate($formParams);
            } else {
                $this->displayLoginForm();
            }
        } elseif ($authAction === Constants::AUTH_CHANGE_PASSWORD_URI_KEY) {
            $formKey = $formParams[self::CHPWD_ACTION_NAME];
            if ($formKey == self::CHPWD_ACTION_VALUE) {
                $this->changePassword($formParams);
            } else {
                RequestManager::redirect();
            }
        } elseif (self::isLoggedIn() && $authAction === Constants::AUTH_LOGOUT_URI_KEY) {
            $this->logout();
        } else {
            RequestManager::redirect(Constants::AUTH_URI_KEY);
        }
    }

    private static function getEncryptedSiteIdentifier() {
        return md5(self::$SESSION_SITE_IDENTIFIER);
    }

    private function authenticate($formParams) {
        $userDetail = $this->getUserDetailsByName($formParams['username']);
        if (!empty($userDetail)) {
            if ($userDetail[Users_DBTable::IS_ACTIVE] == '1') {
                $passwordInDB = $userDetail[Users_DBTable::USER_HASH];
                $passwordHash = $this->getPasswordHash($formParams['password']);
                if ($passwordInDB === $passwordHash) {
                    if ($this->createUserSession($userDetail, $formParams['remember'])) {
                        Logger::getLogger()->LogInfo("Auth Success for userid: ".$formParams['username']);
                        echo ServiceResponse::createServiceResponse(Constants::SUCCESS_RESPONSE, 'Login Successful', '');
                    }
                } else {
                    Logger::getLogger()->LogWarn("Auth Failed [Invalid password] for userid: ".$formParams['username']);
                    echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, Error::AUTH_INVALID_PASSWORD, '');
                }
            } else {
                Logger::getLogger()->LogWarn("Auth Failed [User Inactive] for userid: ".$formParams['username']);
                echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, Error::AUTH_USER_INACTIVE, '');
            }
        } else {
            Logger::getLogger()->LogWarn("Auth Failed [Invalid Username] for userid: ".$formParams['username']);
            echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, Error::AUTH_INVALID_USER_NAME, '');
        }
        exit;
    }

    private function getPasswordHash($password) {
        return hash("sha256", $password);
    }

    private function createUserSession($userDetails, $shouldRememeber) {
        if ($shouldRememeber) {
            Session::setCookieParams(self::SESSION_COOKIE_LIFETIME, '/');
        }
        if (Session::isExist()) {
           Session::regenerateId(true);
        } else {
           Session::start();
        }
        $userDetails[Users_DBTable::IP_ADDRESS] = $_SERVER['REMOTE_ADDR'];
        if ($this->updateUserLogin($userDetails)) {
            Session::set(self::$SESSION_SITE_IDENTIFIER, self::getEncryptedSiteIdentifier());
            Session::set(self::$AUTH_USER_ID_IDENTIFIER, $userDetails[Users_DBTable::USER_NAME]);
            Session::set(Session::SESS_USER_DETAILS, $userDetails);
            return true;
        } else {
            return false;
        }
    }

    private function getUserDetailsByName($username) {
        $query = "SELECT * FROM ".Users_DBTable::DB_TABLE_NAME. " WHERE ";
        $query .= Users_DBTable::USER_NAME." = ? AND ".Users_DBTable::IS_DELETED." = 0";
        $userData = DBManager::executeQuery($query, array($username), true);
        return current($userData);
    }

    private function updateUserLogin($userDetails) {
        $sessionId = Session::getSessionId();
        $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ";
        $query .= Users_DBTable::SESSION_ID. "='" . $sessionId . "', ";
        $query .= Users_DBTable::IP_ADDRESS. "='".$userDetails[Users_DBTable::IP_ADDRESS]."', ";
        $query .= Users_DBTable::IS_LOGGED_IN." = '1', ";
        $query .= Users_DBTable::LAST_LOGIN." = '".date('Y-m-d H:i:s')."' WHERE ";
        $query .= Users_DBTable::USER_ID." = '".$userDetails[Users_DBTable::USER_ID]."'";
        return DBManager::executeQuery($query);
    }

    private function updateUserLogout() {
        $userDetail = Session::get(Session::SESS_USER_DETAILS);
        $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ";
        $query .= Users_DBTable::SESSION_ID. "='', ";
        $query .= Users_DBTable::IP_ADDRESS. "='', ";
        $query .= Users_DBTable::IS_LOGGED_IN." = '0' WHERE ";
        $query .= Users_DBTable::USER_ID." = '".$userDetail[Users_DBTable::USER_ID]."'";
        return DBManager::executeQuery($query);
    }

    private function logout() {
        if ($this->updateUserLogout()) {
            Session::destroy(true);
        }
        RequestManager::redirect();
    }

    private function displayLoginForm() {
        if (!self::isLoggedIn()) {
            $this->smarty->assign('LOGIN_ACTION_VALUE', self::LOGIN_ACTION_VALUE);
            $this->smarty->display("string:".Display::render(self::AUTH_LOGIN_KEY));
        } else {
            RequestManager::redirect();
        }
    }

    public static function isLoggedIn() {
        $ssid = self::getEncryptedSiteIdentifier();
        $ssidInSession = Session::get(self::$SESSION_SITE_IDENTIFIER);
        $sessionUserId = Session::get(self::$AUTH_USER_ID_IDENTIFIER);
        if (($ssid == $ssidInSession) && !empty($sessionUserId)) {
            return true;
        }
        return false;
    }

    public function changePassword($formInputs) {
        if (self::isLoggedIn()) {
            $currentPass = $formInputs['currentpassword'];
            $newPassword = $formInputs['newpassword'];
            $userDetails = Session::get(Session::SESS_USER_DETAILS);
            if ($this->getPasswordHash($currentPass) === $userDetails[Users_DBTable::USER_HASH]) {
                $newPasswordHash = $this->getPasswordHash($newPassword);
                $query = "UPDATE ".Users_DBTable::DB_TABLE_NAME." SET ".
                    Users_DBTable::USER_HASH." = '".$newPasswordHash."' WHERE ".
                    Users_DBTable::USER_ID." = '".$userDetails[Users_DBTable::USER_ID]."'";
                if (DBManager::executeQuery($query)) {
                    echo ServiceResponse::createServiceResponse(Constants::SUCCESS_RESPONSE, Constants::PASSWORD_CHANGED_MSG, '');
                }
            } else {
                echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, Error::ERR_WRONG_PASSWORD, '');
            }
        } else {
            echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, Error::ERR_USER_NOT_LOGGED_IN, '');
        }
        exit;
    }
}