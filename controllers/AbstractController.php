<?php
/**
 * Abstract Controller, meant to be extended by all controllers in app
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since May 11, 2014
 */
abstract class AbstractController {

    abstract protected function run(Resource $resource);

    /**
     * Instance of model class
     */
    protected $model;

    /**
     * Singleton instance of templating engine Smarty
     * 
     * @var Smarty
     */
    protected $smarty;

    public function __construct() {
        $this->smarty = Utils::getSmarty();
    }
    
    /**
     * Returns model instance for the controller
     * 
     * @return AbstractModel
     */
    public function getModel() {
        return $this->model;
    }
}
