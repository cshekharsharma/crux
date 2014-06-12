<?php

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