<?php
/**
 * Index Controller<br>
 * Used as default controller
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since May 08, 2014
 */
class IndexController extends AbstractController {

    const MODULE_KEY = 'index';

    public static $isHomePage = false;

    public function __construct() {
        parent::__construct();
        $this->model = new IndexModel();
        $this->view = new IndexView();
    }
    
    public function run(Resource $resource) {
        Logger::getLogger()->LogInfo("Serving Index Controller");
        $uriParams = $resource->getParams();
        $uc=new UserPreferencesController();
        $uc->getUserPreference(1);
        $programs = $this->getProgramList($uriParams);
        $this->setBean($programs);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    public function getProgramList($inputParams) {
        $programs = array();
        $lang = $inputParams[Constants::INPUT_PARAM_LANG];
        $category = $inputParams[Constants::INPUT_PARAM_CATE];
        $programs = $this->getModel()->getProgramList($lang, $category);
        return $programs;
    }
}