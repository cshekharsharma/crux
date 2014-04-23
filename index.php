<?php
ini_set("display_errors", 0);
require_once 'includes/AutoLoader.php';
Session::start();
require_once 'library/smarty/libs/Smarty.class.php';
RequestManager::initRequest();
Logger::getLogger()->LogInfo("Application Init()");
RequestManager::serveRequest();