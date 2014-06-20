<?php

/**
 * Request Manager class for handling and preprocessing http requests
 *
 * @author Chandra Shekhar <shekahrsharma705@gmail.com>
 * @package includes
 * @since May 11, 2014
 */
class RequestManager {

    const PRIMITIVE_PARAM = "__req";

    const FIRST_PARAM = "__q1__";
    const SECOND_PARAM = "__q2__";
    const THIRD_PARAM = "__q3__";
    const FORTH_PARAM = "__q4__";

    private static $pendingRequestURI;

    /**
     * Initiate request by rearranging request parameters
     * @see $_GET, $_REQUEST
     */
    public static function initRequest() {
        $primitive = self::getParam(self::PRIMITIVE_PARAM);
        $uriParts = explode("/", $primitive);
        for ($i = 0; $i < count($uriParts); $i++) {
            $key = '__q'.($i + 1).'__';
            $_GET[$key] = $_REQUEST[$key] = $uriParts[$i];
        }
        self::requireCoreFiles();
    }

    /**
     * Get http request param for given key
     * 
     * @param unknown $key
     * @param string $includeCookie
     * @return Ambigous <NULL, unknown>
     */
    public static function getParam($key, $includeCookie = false) {
        return self::getNativeParam($key, $includeCookie);
    }

    /**
     * Get parameter from primitive request data containers, i.e. $_GET, $_POST etc
     * 
     * @param unknown $key
     * @param unknown $includeCookie
     * @return unknown|NULL
     */
    private static function getNativeParam($key, $includeCookie) {

        if (!empty($_GET[$key])) {
            return $_GET[$key];
        } elseif (!empty($_POST[$key])) {
            return $_POST[$key];
        } elseif (!empty($_REQUEST[$key])) {
            return $_REQUEST[$key];
        } elseif ($includeCookie && !empty($_COOKIE[$key])) {
            return $_COOKIE[$key];
        } else {
            return null;
        }
    }

    /**
     * Get all request parameters
     * 
     * @return unknown
     */
    public static function getAllParams() {
        return $_REQUEST;
    }

    /**
     * Generic method for serving all http requests
     * 
     * @throws Exception
     */
    public static function serveRequest() {
        $resource = ResourceProvider::getResource();
        $controller = ResourceProvider::getControllerByResourceKey($resource->getKey());
        if (!empty($controller) && $controller instanceof AbstractController) {
            $controller->run($resource);
        } else {
            throw new Exception("Invalid controller requested, Exiting");
        }
    }

    /**
     * HTTP Redirect to certain module.
     * If no module key is given, redirects to index module
     * 
     * @param string $key
     * @param number $statusCode
     */
    public static function redirect($key = "", $statusCode = 302) {
        header("Location: /" . $key);
    }

    /**
     * Get pending urls to be shown just after login
     * @todo: implement using URL params
     * 
     * @return Ambigous <Ambigous, unknown, NULL, unknown>
     */
    public static function getPendingRequestURI() {
        $pendingURI = self::getParam(Session::SESS_PENDING_REQ_URI, true);
        setcookie(Session::SESS_PENDING_REQ_URI, false, 315554400);
        return $pendingURI;
    }

    /**
     * Store current url before going to auth form
     */
    public static function setPendingRequestURI() {
        $uri = RequestManager::getParam(RequestManager::PRIMITIVE_PARAM);
        // hack for avoiding unneccessary favicon.ico http requests from browsers
        if (!empty($uri) && strtolower($uri) != 'favicon.ico') {
            setcookie(Session::SESS_PENDING_REQ_URI, $uri);
        }
    }
    
    /**
     * Load all essential application core files into memory
     */
    public static function requireCoreFiles() {
        require_once 'includes/AutoLoader.php';
        require_once 'library/smarty/libs/Smarty.class.php';
    }
    
    /**
     * handle excpetions and write them to Log file
     * 
     * @param Exception $e
     */
    public static function handleException(Exception $e) {
        Logger::getLogger()->LogError('Exception: '.$e->getMessage());
        Logger::getLogger()->LogError('Exception: '.$e->getTraceAsString());
        self::redirect();
    }
}