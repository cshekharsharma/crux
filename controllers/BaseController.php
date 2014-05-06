<?php

abstract class BaseController {

    abstract protected function run(Resource $resource);

    protected $smarty;

    public function __construct() {
        $this->smarty = Utils::getSmarty();
    }
}