<?php

/**
 * Request Manager class for handling and preprocessing http requests
 *
 * @author Chandra Shekhar <shekahrsharma705@gmail.com>
 * @since May 11, 2014
 */
class RequestManager {

    const PRIMITIVE_PARAM = "__req";

    const FIRST_PARAM = "__q1";
    const SECOND_PARAM = "__q2";
    const THIRD_PARAM = "__q3";
    const FORTH_PARAM = "__q4";

    private static $pendingRequestURI;

    public static function initRequest() {
        $primitive = self::getParam(self::PRIMITIVE_PARAM);
        $uriParts = explode("/", $primitive);
        for ($i = 0; $i < count($uriParts); $i++) {
            $_GET['__q' . ($i + 1)] = $_REQUEST['__q' . ($i + 1)] = $uriParts[$i];
        }
    }

    public static function getParam($key, $includeCookie = false) {
        return self::getNativeParam($key, $includeCookie);
    }

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

    public static function getAllParams() {
        return $_REQUEST;
    }

    public static function serveRequest() {
        self::requireCoreFiles();
        $resource = ResourceProvider::getResource();
        $controller = ResourceProvider::getControllerByResourceKey($resource->getKey());
        if (!empty($controller) && $controller instanceof AbstractController) {
            $controller->run($resource);
        } else {
            Logger::getLogger()->LogFatal("Invalid controller requested, Exiting");
            self::redirect();
        }
    }

    public static function redirect($key = "", $statusCode = 302) {
        header("Location: /" . $key);
    }

    public static function getPendingRequestURI() {
        $pendingURI = self::getParam(Session::SESS_PENDING_REQ_URI, true);
        setcookie(Session::SESS_PENDING_REQ_URI, false, 315554400);
        return $pendingURI;
    }

    public static function setPendingRequestURI() {
        $uri = RequestManager::getParam(RequestManager::PRIMITIVE_PARAM);
        // hack for avoiding unneccessary favicon.ico http requests from browsers
        if (!empty($uri) && strtolower($uri) != 'favicon.ico') {
            setcookie(Session::SESS_PENDING_REQ_URI, $uri);
        }
    }
    
    public static function requireCoreFiles() {
        require_once 'library/smarty/libs/Smarty.class.php';
    }
    
    public static function handleException(Exception $e) {
        Logger::getLogger()->LogError('Exception: '.$e->getMessage());
        Logger::getLogger()->LogError('Exception: '.$e->getTraceAsString());
        self::redirect();
    }
}