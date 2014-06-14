<?php

/**
 * Request Router class for the application
 * Redirects requests to appropriate controller along with appropriate params
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
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
            if (empty($firstParam) && empty($secondParam) && empty($thirdParam)) {
                $resource->setKey(Constants::INDEX_URI_KEY);
            } else {
                $className = ucfirst($firstParam) . 'Controller';
                if (class_exists($className) && is_subclass_of(new $className, 'AbstractController')) {
                    $resource->setKey($className::MODULE_KEY);
                } else {
                    if (!empty($firstParam) && !empty($secondParam) && !empty($thirdParam)) {
                        $resource->setKey(Constants::EXPLORER_URI_KEY);
                    } else {
                        $resource->setKey(Constants::INDEX_URI_KEY);
                    }
                }
            }
        } else {
            $resource->setKey(Constants::AUTH_URI_KEY);
            if ($firstParam !== Constants::AUTH_URI_KEY) {
                RequestManager::setPendingRequestURI();
            }
        }

        $resource = self::setCorrectParams($resource, array($firstParam, $secondParam, $thirdParam));
        return $resource;
    }

    /**
     * Set appropriate parameter for given resource
     * 
     * @param Resource $resource
     * @param array $set
     * @return resource
     */
    private static function setCorrectParams(Resource $resource, array $set) {
        $params = array();
        $altkeys = array(
            Constants::INDEX_URI_KEY,
            Constants::EXPLORER_URI_KEY
        );

        if (in_array($resource->getKey(), $altkeys)) {
            $params = array(
                Constants::INPUT_PARAM_LANG => $set[0],
                Constants::INPUT_PARAM_CATE => $set[1],
                Constants::INPUT_PARAM_PID  => $set[2]
            );
        } else {
            $params = array(
                Constants::INPUT_PARAM_MODULE    => $set[0],
                Constants::INPUT_PARAM_ACTION    => $set[1],
                Constants::INPUT_PARAM_SUBACTION => $set[2]
            );
        }
        $resource->setParams($params);
        return $resource;
    }

    /**
     * Get requested controller object
     *
     * @param string $key
     * @return AbstractController|NULL
     */
    public static function getControllerByResourceKey($key) {
        $controllerClass = ucfirst(strtolower(($key))) . 'Controller';
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if ($controller instanceof AbstractController) {
                return $controller;
            }
        }
        return null;
    }
}