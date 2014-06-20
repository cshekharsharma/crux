<?php
/**
 * Controller class for UserPreference module
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class UserPreferencesController extends AbstractController {

    const MODULE_KEY = 'userPreferences';

    private $userPreferenceKeys;

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (strtolower($uriParams[Constants::INPUT_PARAM_ACTION]) === 'save') {
            if ($this->saveUserPreference($formParams)) {
                Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_UPDATE);
            } else {
                Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::ERROR_OPERATION_FAILED);
            }
        }
    }

    /**
     * Save user preference in database
     * 
     * @param array $formParams
     * @return boolean
     */
    private function saveUserPreference(array $formParams) {
        $preferenceArr = array();
        $userID = Utils::getLoggedInUserId();
        $preferenceArr[PreferenceKeys::CODE_EDITOR_THEME] = $formParams[PreferenceKeys::CODE_EDITOR_THEME];
        $content = $this->encodeContents($preferenceArr);
        $this->flushAll($userID);
        $query = 'INSERT INTO '.UserPreferences_DBTable::DB_TABLE_NAME;
        $query .= ' VALUES("", ?, ?, NOW(), NOW(), 0);';
        if (DBManager::executeQuery($query, array($userID, $content))) {
            Session::set(Session::SESS_USER_PREF_KEY, $preferenceArr);
            return true;
        }
        return false;
    }
    
    /**
     * Get user preference value for given key
     * 
     * @param unknown $key
     * @return Ambigous <>|NULL
     */
    public static function get($key) {
        $allPreferences = Session::get(Session::SESS_USER_PREF_KEY);
        if (!empty($allPreferences[$key])) {
            return $allPreferences[$key];
        } else {
            return null;
        }
    }
    
    /**
     * Get user pref values from database
     * 
     * @param int $userId
     * @return mixed
     */
    public function getUserPreferenceFromDB($userId) {
        $query = 'SELECT * FROM '.UserPreferences_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= UserPreferences_DBTable::USER_ID.' =? AND '.UserPreferences_DBTable::IS_DELETED.'=0';
        return current(DBManager::executeQuery($query, array($userId), true));
    }
    
    /**
     * Get all user preferences for given user 
     * 
     * @param unknown $userId
     * @return Ambigous <multitype:, mixed>
     */
    public function getUserPreference($userId) {
        $userPref = $this->getUserPreferenceFromDB($userId);
        $preferenceArr = $this->decodeContents($userPref[UserPreferences_DBTable::CONTENTS]);
        return $preferenceArr;
    }

    /**
     * Flush all user preference for given user Id
     * 
     * @param int $userId
     */
    private function flushAll($userId) {
        $query = 'DELETE FROM '.UserPreferences_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= UserPreferences_DBTable::USER_ID . '=?';
        if (DBManager::executeQuery($query, array($userId))) {
            Session::remove(Session::SESS_USER_PREF_KEY);
        }
    }

    /**
     * Encode preferences to be saved in database
     * 
     * @param array $contentArray
     * @return string
     */
    private function encodeContents(array $contentArray) {
        return base64_encode(json_encode($contentArray));
    }
    
    /**
     * Decode preferences for further use
     * 
     * @param string $content
     * @return array
     */
    private function decodeContents($content) {
        return json_decode(base64_decode($content), true);
    }
}