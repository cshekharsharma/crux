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
        self::normalizeRequest();
    }

    /**
     * Get http request param for given key
     *
     * @param string $key
     * @param boolean $includeCookie
     * @return array|null
     */
    public static function getParam($key, $includeCookie = false) {
        return self::getNativeParam($key, $includeCookie);
    }

    /**
     * Get parameter from primitive request data containers, i.e. $_GET, $_POST etc
     *
     * @param string $key
     * @param boolean $includeCookie
     * @return array|NULL
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
     * @return array
     */
    public static function getAllParams() {
        return $_REQUEST;
    }

    /**
     * Returns all <b>POST</b> parameters
     * 
     * @return array
     */
    public static function getGetParams() {
        return $_GET;
    }

    /**
     * Returns all <b>GET</b> parameters 
     * 
     * @return array
     */
    public static function getPostParams() {
        return $_POST;
    }

    /**
     * remove slashes from all http input containers to avoid injection and attacks
     */
    public static function normalizeRequest() {
        if(isset($_GET))
            $_GET = Utils::stripSlashes($_GET);
        if(isset($_POST))
            $_POST = Utils::stripSlashes($_POST);
        if(isset($_COOKIE))
            $_COOKIE = Utils::stripSlashes($_COOKIE);
        if(isset($_REQUEST))
            $_REQUEST = Utils::stripSlashes($_REQUEST);
    }

    /**
     * Tells if request is an AJAX request, by checking appropriate header
     *
     * @return boolean
     */
    public static function isAjaxRequest() {
        $hasHeader = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $isAjaxHeader = ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
        return $hasHeader && $isAjaxHeader;
    }

    public static function isValidRequest() {
        if (!Utils::isEmpty(self::getPostParams())) {
            if (!Utils::isEmpty(self::getParam(Constants::CSRF_TOKEN_NAME))) {
                $formToken = self::getParam(Constants::CSRF_TOKEN_NAME);
                $sessToken = self::getCsrfToken();
                if ($sessToken === $formToken) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Generic method for serving all http requests
     *
     * @throws Exception
     */
    public static function serveRequest() {
        self::regenerateCsrfToken();
        if (self::isValidRequest()) {
            $resource = ResourceProvider::getResource();
            $controller = ResourceProvider::getControllerByResourceKey($resource->getKey());
            if (!empty($controller) && $controller instanceof AbstractController) {
                $controller->run($resource);
            } else {
                throw new Exception("Invalid controller requested, Exiting");
            }
        } else {
            Logger::getLogger()->logWarn('No CSRF token found. IP:' . print_r($_SERVER['REMOTE_ADDR'], true));
            if (self::isAjaxRequest()) {
                Response::sendFailureResponse('Something went wrong!');
            } else {
            }
        }
    }

    /**
     * If csrf token not present in session create a new and save in session
     * 
     * @param string $forceful
     */
    public static function regenerateCsrfToken($forceful = false) {
        $tokenName = Constants::CSRF_TOKEN_NAME;
        if (Utils::isEmpty(self::getCsrfToken()) || $forceful) {
            $token = Utils::getRandomString();
            Session::set($tokenName, $token);
        }
    }

    /**
     * Get valid csrf token for current session
     * 
     * @return string|NULL
     */
    public static function getCsrfToken() {
        if (!Utils::isEmpty(Session::get(Constants::CSRF_TOKEN_NAME))) {
            return Session::get(Constants::CSRF_TOKEN_NAME);
        } else {
            return null;
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
     * Terminate current request processing, And redirects to home page
     * 
     * @param string $msg
     */
    public static function exitRequest($msg) {
        Logger::getLogger()->logError('Exiting Request: ' . $msg);
        RequestManager::redirect();
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