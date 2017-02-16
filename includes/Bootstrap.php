<?php

/**
 * Application bootstrapping class
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since Sep 9, 2014
 */
class Bootstrap {

    /**
     * Bootstrapping application
     */
    public function init() {
        RequestManager::initRequest();
        $this->requireCoreFiles();
        RequestManager::serveRequest();
    }
    /**
     * Load all essential application core files into memory
     */
    public function requireCoreFiles() {
        $smartyVersion=(version_compare(PHP_VERSION, '7.0.0') < 0) ? '3.1.16' : '3.1.29';
        require_once 'library/smarty-'.$smartyVersion.'/libs/Smarty.class.php';
    }
}
