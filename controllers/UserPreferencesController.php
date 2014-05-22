<?php

class UserPreferencesController extends BaseController {

    const MODULE_KEY = 'userPreferences';
    const PREF_ACTION = 'pref_action';

    private $userPreferenceKeys;

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
//         Utils::displayVariableValues($)
        if (strtolower($uriParams[self::PREF_ACTION]) === 'save') {
            if ($this->saveUserPreference($formParams)) {
                die(Response::createResponse(Constants::SUCCESS_RESPONSE, 'Successfully Updated!', ''));
            } else {
                die(Response::createResponse(Constants::SUCCESS_RESPONSE, 'Operation failed!', ''));
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
            Session::get(Session::SESS_USER_PREF_KEY, $preferenceArr);
            return true;
        }
        return false;
    }
    
    public function getUserPreference($userId) {
        $query = 'SELECT * FROM '.UserPreferences_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= UserPreferences_DBTable::USER_ID.' =? AND '.UserPreferences_DBTable::IS_DELETED.'=0';
        return DBManager::executeQuery($query, array($userId), true);
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
}