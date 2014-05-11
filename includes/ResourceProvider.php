<?php

/**
 * Request Router class for the application
 * Redirects requests to appropriate controller along with appropriate params
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since May 11, 2014
 */
class ResourceProvider {

    /**
     * Routes the request to appropriate controller and returns resource params
     *
     * @return Resource $resource
     */
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
            } elseif ($firstParam === Constants::STATS_URI_KEY) {
                $resource->setKey(Constants::STATS_URI_KEY);
                if (!empty($secondParam)) {
                    $resource->setParams(
                        array(
                            Constants::INPUT_PARAM_USER => $secondParam
                        )
                    );
                } else {
                    $resource->setParams(false);
                }
            } elseif ($firstParam === Constants::DOWNLOAD_URI_KEY) {
                $resource->setKey(Constants::DOWNLOAD_URI_KEY);
                $resource->setParams(
                    array(
                        Constants::INPUT_PARAM_PID => $secondParam
                    )
                );
            } elseif ($firstParam === Constants::EDITOR_URI_KEY) {
                $resource->setKey(Constants::EDITOR_URI_KEY);
                $resource->setParams(
                    array(
                        Constants::INPUT_PARAM_PID => $secondParam
                    )
                );
            } elseif ($firstParam === Constants::EXPLORER_URI_KEY) {
                $isDelete = ($secondParam === Constants::DELETE_URI_KEY);
                $hasPID = (is_numeric($thirdParam));
                if ($isDelete && $hasPID) {
                    $resource->setKey(Constants::EXPLORER_URI_KEY);
                    $resource->setParams(
                        array(
                            Constants::INPUT_PARAM_PID => $thirdParam
                        )
                    );
                } else {
                    $resource->setKey(Constants::INDEX_URI_KEY);
                    $resource->setParams(false);
                }
            } elseif ($firstParam === Constants::SEARCH_URI_KEY) {
                $resource->setKey(Constants::SEARCH_URI_KEY);
                $resource->setParams(RequestManager::getAllParams());
            } elseif (!empty($firstParam) && !empty($secondParam) && !empty($thirdParam)) {
                $resource->setKey(Constants::EXPLORER_URI_KEY);
                $resource->setParams(
                    array(
                        Constants::INPUT_PARAM_LANG => $firstParam,
                        Constants::INPUT_PARAM_CATE => $secondParam,
                        Constants::INPUT_PARAM_PID => $thirdParam
                    )
                );
            } else {
                $resource->setKey(Constants::INDEX_URI_KEY);
                $resource->setParams(
                    array(
                        Constants::INPUT_PARAM_LANG => $firstParam,
                        Constants::INPUT_PARAM_CATE => $secondParam,
                        Constants::INPUT_PARAM_PID => $thirdParam
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

    /**
     * Get requested controller object
     *
     * @param string $key
     * @return BaseController|NULL
     */
    public static function getControllerByResourceKey($key) {
        $controllerClass = ucfirst(strtolower(($key))) . 'Controller';
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if ($controller instanceof BaseController) {
                return $controller;
            }
        }
        return null;
    }
}