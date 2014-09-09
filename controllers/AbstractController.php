<?php
/**
 * Abstract Controller, meant to be extended by all controllers in app
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since May 11, 2014
 */
abstract class AbstractController extends Application {

    /**
     * Init method for all the controllers
     * 
     * @param Resource $resource
     */
    abstract protected function run(Resource $resource);

    /**
     * Instance of model class
     */
    protected $model;

    /**
     * Instance of View class
     */
    protected $view;
    
    /**
     * Singleton instance of templating engine Smarty
     *
     * @var Smarty
     */
    protected $smarty;

    /**
     * Constructor
     */
    public function __construct() {
        $this->smarty = Display::getSmarty();
    }

    /**
     * Returns model instance for the controller
     */
    public function getModel() {
        return $this->model;
    }
    
    /**
     * Returns view instance for the controller
     */
    public function getView() {
        return $this->view;
    }
}