<?php

class IndexController extends AbstractController {

    const MODULE_KEY = 'index';

    public static $isHomePage = false;

    public function __construct() {
        parent::__construct();
        $this->model = new IndexModel();
    }
    
    public function run(Resource $resource) {
        Logger::getLogger()->LogInfo("Serving Index Controller");
        $uriParams = $resource->getParams();
        $uc=new UserPreferencesController();
        $uc->getUserPreference(1);
        $this->displayProgramList($uriParams);
    }

    public function displayProgramList($inputParams) {
        $programs = array();
        $lang = $inputParams[Constants::INPUT_PARAM_LANG];
        $category = $inputParams[Constants::INPUT_PARAM_CATE];
        $programs = $this->getModel()->getProgramList($lang, $category);
        $markup = $this->populateTemplateMarkup($programs);
    }

    private function populateTemplateMarkup($programList) {
        if (!empty($programList)) {
            foreach ($programList as $key => $programData) {
                $descKey = ProgramDetails_DBTable::DESCRIPTION;
                $desc = $programData[$descKey];
                $parsedDesc = Utils::createLinks($desc);
                $programList[$key][$descKey] = $parsedDesc;
            }
            $this->smarty->assign("PROGRAM_LIST", $programList);
            $this->smarty->display("string:". Display::render(self::MODULE_KEY));
        } else {
            if (self::$isHomePage) {
                $this->smarty->display("string:". Display::render("EMPTY_CODEBASE"));
            } else {
                $this->smarty->display("string:". Display::render("NO_ITEM_FOUND"));
            }
        }
    }
}