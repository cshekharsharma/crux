<?php
/**
 * Resource for IPC between app router and module controllers
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package helpers
 * @subpackage classes
 * @since May 17, 2014
 */
class Resource {
    
    private $key;
    private $params;
    
    public function getKey() {
        return $this->key;
    }
    
    public function setKey($key) {
        $this->key = $key;
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function setParams($params) {
        $this->params = $params;
    }
}