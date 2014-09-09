<?php 

/**
 * Application bootstrapping class
 * 
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
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
        require_once 'library/smarty/libs/Smarty.class.php';
    }
}