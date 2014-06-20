<?php

/**
 * Application initialization!<br>
 * Every single fucking reqeust goes from here,
 * and then gets routed to appropriate controller
 */
try {
    ini_set("display_errors", 0);
    RequestManager::initRequest();
    Session::start();
    RequestManager::serveRequest();
} catch(Exception $e) {
    RequestManager::handleException($e);
}
