<?php

class ResourceProvider {

    public static function getResource() {
        $resource = new Resource();
        $firstParam = RequestManager::getParam(RequestManager::FIRST_PARAM);
        $secondParam = RequestManager::getParam(RequestManager::SECOND_PARAM);
        $thirdParam = RequestManager::getParam(RequestManager::THIRD_PARAM);

        if (AuthController::isLoggedIn()) {

            if (($firstParam === Constants::INDEX_URI_KEY) ||
                (empty($firstParam) && empty($secondParam) && empty($thirdParam))) {
                $resource->setKey(Constants::INDEX_URI_KEY);
                $resource->setParams(false);
            } elseif ($firstParam === Constants::AUTH_URI_KEY) {
                $resource->setKey(Constants::AUTH_URI_KEY);
                $resource->setParams(
                    array(
                        AuthController::AUTH_ACTION => $secondParam
                    )
                );
            } elseif ($firstParam === Constants::UPLOAD_URI_KEY) {
                $resource->setKey(Constants::UPLOAD_URI_KEY);
                $resource->setParams(false);
            } elseif ($firstParam === Constants::DOWNLOAD_URI_KEY) {
                $resource->setKey(Constants::DOWNLOAD_URI_KEY);
                $resource->setParams(
                    array(
                        RequestManager::INPUT_PARAM_PID => $secondParam
                    )
                );
            } elseif ($firstParam === Constants::EDITOR_URI_KEY) {
                $resource->setKey(Constants::EDITOR_URI_KEY);
                $resource->setParams(
                    array(
                        EditorController::PID => $secondParam
                    )
                );
            } elseif ($firstParam === Constants::SEARCH_URI_KEY) {
                $resource->setKey(Constants::SEARCH_URI_KEY);
                $resource->setParams(RequestManager::getAllParams());
            } elseif (!empty($firstParam) && !empty($secondParam) && !empty($thirdParam)) {
                $resource->setKey(Constants::EXPLORER_URI_KEY);
                $resource->setParams(
                    array(
                        RequestManager::INPUT_PARAM_LANG => $firstParam,
                        RequestManager::INPUT_PARAM_CATE => $secondParam,
                        RequestManager::INPUT_PARAM_PID => $thirdParam
                    )
                );
            } else {
                $resource->setKey(Constants::INDEX_URI_KEY);
                $resource->setParams(
                    array(
                        RequestManager::INPUT_PARAM_LANG => $firstParam,
                        RequestManager::INPUT_PARAM_CATE => $secondParam,
                        RequestManager::INPUT_PARAM_PID => $thirdParam
                    )
                );
            }
        } else {
            if ($firstParam === Constants::AUTH_URI_KEY) {
                $resource->setKey(Constants::AUTH_URI_KEY);
                $resource->setParams(
                    array(
                        AuthController::AUTH_ACTION => $secondParam
                    )
                );
            } else {
                RequestManager::redirect(Constants::AUTH_URI_KEY);
            }
        }
        return $resource;
    }

    public static function getControllerByResourceKey($key) {
        $controllerClass = ucfirst(strtolower(($key))) . 'Controller';
        $controller = new $controllerClass();
        return $controller;
    }


}