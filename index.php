<?php

/**
 * Application initialization!<br>
 * Every single fucking request goes from here,
 * and then gets routed to appropriate controller
 */
try {
    ini_set("display_errors", 0);
    require_once 'includes/AutoLoader.php';
    Session::start();
    Profiler::startProfiler('main');
    RequestManager::initRequest();
    RequestManager::serveRequest();
    Profiler::endProfiler('main');
} catch(Exception $e) {
    RequestManager::handleException($e);
    Profiler::endProfiler('main');
}
