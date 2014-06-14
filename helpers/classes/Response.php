<?php

/**
 * Generic response type for AJAX requests and API calls
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package helpers
 * @subpackage classes
 * @since May 11, 2014
 */
class Response {

    private function __construct() {
    }

    /**
     * Creates generic JSON response from provided data and exits after printing.
     *
     * @param string $code
     * @param string $msg
     * @param string $detail
     */
    public static function sendResponse($code, $msg, $detail = '') {
        header('content-type: application/json; charset=utf-8');
        echo self::getResponse($code, $msg, $detail);
        exit();
    }


    /**
     * Creates generic JSON response from provided data
     *
     * @param string $code
     * @param string $msg
     * @param string $detail
     * @return string $response
     */
    public static function getResponse($code, $msg, $detail = '') {
        $response = array(
            'code' => $code,
            'msg' => $msg,
            'detail' => $detail
        );

        return json_encode($response);
    }
}