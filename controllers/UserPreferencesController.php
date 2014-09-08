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
        $preferenceArr[PreferenceKeys::CODE_EDITOR_SHOW_INVISIBLE] = $formParams[PreferenceKeys::CODE_EDITOR_SHOW_INVISIBLE];
        $preferenceArr[PreferenceKeys::PAGINATOR_LIMIT] = $formParams[PreferenceKeys::PAGINATOR_LIMIT];
        $content = $this->encodeContents($preferenceArr);
        $this->flushAll($userID);
        $insertArr = array(
            UserPreferences_DBTable::RECORD_ID   => '',
            UserPreferences_DBTable::USER_ID     => $userID,
            UserPreferences_DBTable::CONTENTS    => $content,
            UserPreferences_DBTable::CREATED_ON  => Utils::getCurrentDatetime(),
            UserPreferences_DBTable::MODIFIED_ON => Utils::getCurrentDatetime(),
            UserPreferences_DBTable::IS_DELETED  => '0'
        );
        $tableName = UserPreferences_DBTable::DB_TABLE_NAME;
        if (DBManager::insert($tableName, $insertArr, array())) {
            Session::set(Session::SESS_USER_PREF_KEY, $preferenceArr);
            return true;
        }
        return false;
    }
    
    /**
     * Get user preference value for given key
     * 
     * @param string $key
     * @return string|NULL
     */
    public static function get($key) {
        $userId = Session::get(Session::SESS_USER_DETAILS)[Users_DBTable::USER_ID];
        $allPreferences = Session::get(Session::SESS_USER_PREF_KEY);
        if (!empty($allPreferences)) {
            $userPrefRow = (new UserPreferencesController())->getUserPreferenceFromDB($userId);
            $allPreferences = $userPrefRow[UserPreferences_DBTable::CONTENTS];
            $allPreferences = (new UserPreferencesController())->decodeContents($allPreferences);
            Session::set(Session::SESS_USER_PREF_KEY, $allPreferences);
        }
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
    public function encodeContents(array $contentArray) {
        return base64_encode(json_encode($contentArray));
    }
    
    /**
     * Decode preferences for further use
     * 
     * @param string $content
     * @return array
     */
    public function decodeContents($content) {
        return json_decode(base64_decode($content), true);
    }
}