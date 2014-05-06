<?php

class ServiceResponse {

    private function __construct() {

    }

    public static function createServiceResponse($code, $msg, $detail) {
        $serviceResponse = array(
            'code' => $code,
            'msg' => $msg,
            'detail' => $detail
        );
        
        return json_encode($serviceResponse);
    }
}