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
    
    private static $PENDING_REQUEST_URI_KEY = false;
    
    public static function initRequest() {
        $primitive = self::getParam(self::PRIMITIVE_PARAM);
        $uriParts = explode("/", $primitive);
        for ($i = 0; $i < count($uriParts); $i++) {
            $_GET['__q' . ($i + 1)] = $_REQUEST['__q' . ($i + 1)] = $uriParts[$i];
        }
    }
    
    public static function getParam($key) {
        return self::getNativeParam($key);
    }

    private static function getNativeParam($key) {

        if (!empty($_GET[$key])) {
            return $_GET[$key];
        } elseif (!empty($_POST[$key])) {
            return $_POST[$key];
        } elseif (!empty($_REQUEST[$key])) {
            return $_REQUEST[$key];
        } else {
            return null;
        }
    }

    public static function getAllParams() {
        return $_REQUEST;
    }
    
    public static function serveRequest() {
        Logger::getLogger()->LogInfo("Serving INIT Request");
        $resource = ResourceProvider::getResource();
        $controller = ResourceProvider::getControllerByResourceKey($resource->getKey());
        $controller->run($resource);
    }
    
    public static function redirect($key = "") {
        header("Location: /" . $key);
    }
    
    public static function setPendingRequestURI($key) {
        self::$PENDING_REQUEST_URI_KEY = $key;
    }
    
    public static function getPendingRequestURI() {
        return self::$PENDING_REQUEST_URI_KEY;
    }
}