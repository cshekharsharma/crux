<?php
/**
 * Session Manager class for basic session interaction of app
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class Session {

    const SESS_USER_DETAILS = 'loggedin_user_details';
    const SESS_AUTH_USER_KEY = 'auth_user_id';
    const SESS_USER_PREF_KEY = 'user_pref_data';
    const SESS_SEARCH_SUGGESTIONS = 'search_suggestions';
    const SESS_SMARTY_INSTANCE = 'smarty_instance';
    const SESS_PENDING_REQ_URI = 'pending_request_uri';
    const SESS_EMPTY_STATS_MATRIX = 'empty_lang_category_matrix';
    const SESS_ID_NAME_TRANSLATION_MAP = 'id_name_translation_map';

    private static $_isSessionRunning = false;

    public static function get($key) {
        $sessionValue = null;
        if (!empty($_SESSION[$key])) {
            $sessionValue = $_SESSION[$key];
        } else {
            Logger::getLogger()->LogWarn("Attempting to get invalid item from Session with key : ".$key);
        }
        return $sessionValue;
    }
    
    public static function remove($key) {
        if (!is_null(Session::get($key))) {
            unset($_SESSION[$key]);
        }
    }

    public static function getSessionId($id = null) {
        return session_id();
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function isExist() {
        return ((self::$_isSessionRunning) && !empty($_COOKIE[session_name()]));
    }

    public static function start() {
        $isSessionStart = session_start();
        if ($isSessionStart) {
            self::$_isSessionRunning = true;
            Logger::getLogger()->LogDebug("Session Started / Resumed with ID : " . Session::getSessionId());
        }
        return $isSessionStart;
    }

    public static function regenerateId($shouldDeleteOld = false) {
        $newSessionId = session_regenerate_id($shouldDeleteOld);
        if ($newSessionId) {
            Logger::getLogger()->LogDebug("Session ID regenerated, New Session ID: ". $newSessionId);
        }
        return $newSessionId;
    }

    public static function destroy($deleteCookies = true) {
        if ($deleteCookies) {
            if (isset($_COOKIE[session_name()])) {
                $params = session_get_cookie_params();
                // Setting cookie expiry date to '1980-01-01'
                setcookie(session_name(), false, 315554400, $params['path'], $params['domain'], $params['secure']);
            }
        }
        $isDestroyed = session_destroy();
        if ($isDestroyed) {
            self::$_isSessionRunning = false;
            Logger::getLogger()->LogDebug('Session with ID : ' . Session::getSessionId() . 'is closing');
        }
        return $isDestroyed;
    }

    public static function setCookieParams($lifetime = 0, $path = null, $domain = null, $httpOnly = null) {
        session_set_cookie_params($lifetime, $path, $domain, $httpOnly);
    }

    public static function getCookieParams() {
        return session_get_cookie_params();
    }
}