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

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        $uc = new UserPreferencesController();
        $uc->getUserPreference(Utils::getLoggedInUserId());
        $programs = $this->getProgramList($uriParams, $formParams);
        $this->setBean($programs);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    /**
     * Get list of all active programs in system
     *
     * @param array $inputParams
     * @return boolean
     */
    public function getProgramList(array $inputParams, $formParams) {
        $programs = array();
        $lang = $inputParams[Constants::INPUT_PARAM_LANG];
        $category = $inputParams[Constants::INPUT_PARAM_CATE];
        $offset = empty($formParams['offset']) ? 0 : $formParams['offset'];
        $programs = $this->getModel()->getProgramList($lang, $category, $offset);
        return $programs;
    }
}