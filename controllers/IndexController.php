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
        $this->populateProgramData($uriParams, $formParams);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    /**
     * Get list of all active programs in system
     *
     * @param array $inputParams
     */
    public function populateProgramData(array $inputParams, $formParams) {
        $programs = array();
        $language = $inputParams[Constants::INPUT_PARAM_LANG];
        $category = $inputParams[Constants::INPUT_PARAM_CATE];
        $isValidRequest = $this->isValidProgramRequest($language, $category);
        $offset = empty($formParams['offset']) ? 0 : $formParams['offset'];
        $programs = $this->getModel()->getProgramList($language, $category, $offset);
        $this->setBean(
            array(
                'programList' => $programs,
                'isValidRequest' => $isValidRequest
            )
        );
    }
    
    public function isValidProgramRequest($language, $category) {
        $isValid = false;
        if (empty($language) && empty($category)) {
            $isValid = true;
        } elseif (!empty($language) && !empty($category)) {
            $hasLanguage = (new LanguageController())->languageExists($language);
            $hasCategory = (new CategoryController())->categoryExists($category);
            $isValid = $hasCategory && $hasLanguage;
        } elseif (!empty($language) && empty($category)) {
            $isValid = (new LanguageController())->languageExists($language);
        } else {
            $isValid = false;
        }
        return $isValid;
    }
}