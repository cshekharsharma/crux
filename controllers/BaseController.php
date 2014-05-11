<?php
/**
 * Base Controller, meant to be extended by all controllers in app
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since May 11, 2014
 */
abstract class BaseController {

    abstract protected function run(Resource $resource);

    /**
     * @var Smarty
     */
    protected $smarty;

    public function __construct() {
        $this->smarty = Utils::getSmarty();
    }
}