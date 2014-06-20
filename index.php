<?php

/**
 * Application initialization!<br>
 * Every single fucking request goes from here,
 * and then gets routed to appropriate controller
 */
try {
    ini_set("display_errors", 0);
    require_once 'includes/AutoLoader.php';
    RequestManager::initRequest();
    Session::start();
    RequestManager::serveRequest();
} catch(Exception $e) {
    RequestManager::handleException($e);
}
