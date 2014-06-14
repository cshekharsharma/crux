<?php
/**
 * Authentication Controller
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since May 14, 2014
 */
class AuthController extends AbstractController {

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

    public function __construct() {
        parent::__construct();
        $this->model = new AuthModel();
    }

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        $this->redirectAuthRequest($uriParams, $formParams);
    }

    private function redirectAuthRequest ($uriParams, $formParams) {
        $authAction = $uriParams[Constants::INPUT_PARAM_ACTION];
        if (empty($authAction) || $authAction === Constants::AUTH_LOGIN_URI_KEY) {
            $formKey = $formParams[self::LOGIN_ACTION_NAME];
            $isValidKey = ($formKey === self::LOGIN_ACTION_VALUE);
            if (!self::isLoggedIn() && $isValidKey && Utils::isAjaxRequest()) {
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
        $nextURL = '';
        $userDetail = $this->getModel()->getUserDetailsByName($formParams['username']);
        if (!empty($userDetail)) {
            if ($userDetail[Users_DBTable::IS_ACTIVE] == '1') {
                $passwordInDB = $userDetail[Users_DBTable::USER_HASH];
                $passwordHash = $this->getPasswordHash($formParams['password']);
                if ($passwordInDB === $passwordHash) {
                    if ($this->createUserSession($userDetail, $formParams['remember'])) {
                        Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_LOGIN, $nextURL);
                        Logger::getLogger()->LogInfo("Auth Success for userid: ".$formParams['username']);
                    }
                } else {
                    Logger::getLogger()->LogWarn("Auth Failed [Invalid password] for userid: ".$formParams['username']);
                    Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_AUTH_INVALID_PASSWORD);
                }
            } else {
                Logger::getLogger()->LogWarn("Auth Failed [User Inactive] for userid: ".$formParams['username']);
                Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_AUTH_USER_INACTIVE);
            }
        } else {
            Logger::getLogger()->LogWarn("Auth Failed [Invalid Username] for userid: ".$formParams['username']);
            Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_AUTH_INVALID_USER_NAME);
        }
    }

    public function getPasswordHash($password) {
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
        $uc = new UserPreferencesController();
        if ($this->getModel()->updateUserLogin($userDetails)) {
            Session::set(self::$SESSION_SITE_IDENTIFIER, self::getEncryptedSiteIdentifier());
            Session::set(self::$AUTH_USER_ID_IDENTIFIER, $userDetails[Users_DBTable::USER_NAME]);
            Session::set(Session::SESS_USER_DETAILS, $userDetails);
            Session::set(Session::SESS_USER_PREF_KEY, $uc->getUserPreference($userDetails[Users_DBTable::USER_ID]));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Action: User logout
     * 
     * @param string $userId
     * @param string $redirect
     */
    private function logout($userId = null, $redirect = true) {
        if ($this->getModel()->updateUserLogout($userId)) {
            Session::destroy(true);
        }

        if ($redirect) {
            RequestManager::redirect();
        }
    }

    /**
     * Display login form
     */
    private function displayLoginForm() {
        if (!self::isLoggedIn()) {
            $this->smarty->assign('LOGIN_ACTION_VALUE', self::LOGIN_ACTION_VALUE);
            $this->smarty->display("string:".Display::render(self::AUTH_LOGIN_KEY));
        } else {
            RequestManager::redirect();
        }
    }

    /**
     * Checks if user is logged in or not
     * 
     * @return boolean
     */
    public static function isLoggedIn() {
        $ssid = self::getEncryptedSiteIdentifier();
        $ssidInSession = Session::get(self::$SESSION_SITE_IDENTIFIER);
        $sessionUserId = Session::get(self::$AUTH_USER_ID_IDENTIFIER);
        if (($ssid == $ssidInSession) && !empty($sessionUserId)) {
            return true;
        }
        return false;
    }

    /**
     * Action: Change password
     * 
     * @param array $formInputs
     */
    public function changePassword(array $formInputs) {
        if (self::isLoggedIn()) {
            $currentPass = $formInputs['currentpassword'];
            $newPassword = $formInputs['newpassword'];
            $userDetails = Session::get(Session::SESS_USER_DETAILS);
            if ($this->getPasswordHash($currentPass) === $userDetails[Users_DBTable::USER_HASH]) {
                $newPasswordHash = $this->getPasswordHash($newPassword);
                if ($this->getModel()->updateUserPassword($userDetails, $newPasswordHash)) {
                    Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_PASSWORD_CHANGED);
                }
            } else {
                Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_WRONG_PASSWORD);
            }
        } else {
            Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_USER_NOT_LOGGED_IN);
        }
    }
}