<?php

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

    private function saveUserPreference($formParams) {
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
    
    public static function get($key) {
        $allPreferences = Session::get(Session::SESS_USER_PREF_KEY);
        if (!empty($allPreferences[$key])) {
            return $allPreferences[$key];
        } else {
            return null;
        }
    }
    
    public function getUserPreferenceFromDB($userId) {
        $query = 'SELECT * FROM '.UserPreferences_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= UserPreferences_DBTable::USER_ID.' =? AND '.UserPreferences_DBTable::IS_DELETED.'=0';
        return current(DBManager::executeQuery($query, array($userId), true));
    }
    
    public function getUserPreference($userId) {
        $userPref = $this->getUserPreferenceFromDB($userId);
        $preferenceArr = $this->decodeContents($userPref[UserPreferences_DBTable::CONTENTS]);
        return $preferenceArr;
    }

    private function flushAll($userId) {
        $query = 'DELETE FROM '.UserPreferences_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= UserPreferences_DBTable::USER_ID . '=?';
        if (DBManager::executeQuery($query, array($userId))) {
            Session::remove(Session::SESS_USER_PREF_KEY);
        }
    }

    private function encodeContents(array $contentArray) {
        return base64_encode(json_encode($contentArray));
    }
    
    private function decodeContents($content) {
        return json_decode(base64_decode($content), true);
    }
}