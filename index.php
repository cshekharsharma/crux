<?php

/**
 * Application initialization!<br>
 * Every single fucking reqeust goes from here,
 * and then gets routed to appropriate controller
 */
try {
    ini_set("display_errors", 0);
    require_once 'includes/AutoLoader.php';
    require_once 'includes/Debugger.php';
    Session::start();
    RequestManager::initRequest();
    RequestManager::serveRequest();
    sayHello();
} catch(Exception $e) {
    RequestManager::handleException($e);
}

function sayHello() {
    $a = new stdClass();
    Utils::debugVariable($a);
}