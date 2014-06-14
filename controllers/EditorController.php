<?php

class EditorController extends AbstractController {

    const MODULE_KEY = 'editor';

    const EDIT_ACTION_NAME = 'edit_action_name';
    const EDIT_ACTION_VALUE = 'editCode_byajax_WBNFLZCAN';
    const IS_UPDATE_VALUE = "isupdate";

    public function __construct() {
        parent::__construct();
        $this->model = new EditorModel();
    }
    
    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!$this->isAjaxRequest($formParams)) {
            if (is_numeric($uriParams[Constants::INPUT_PARAM_ACTION])) {
                $this->displayCodeEditor($uriParams[Constants::INPUT_PARAM_ACTION]);
            } else {
                $this->displayCodeEditor();
            }
        } else {
            if (!$this->isUpdateRequest($formParams)) {
                $this->getModel()->submitCode($formParams);
            } else {
                $this->getModel()->updateCode($formParams);
            }
             
        }
    }

    private function displayCodeEditor($pid = false) {
        $categoryList = ResourceProvider::getControllerByResourceKey(CategoryController::MODULE_KEY)->getCategoryList();
        $languageList = ResourceProvider::getControllerByResourceKey(LanguageController::MODULE_KEY)->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("LEVEL_LIST", array('Easy', 'Average', 'Difficult'));
        $this->smarty->assign("EDIT_ACTION_NAME", self::EDIT_ACTION_NAME);
        $this->smarty->assign("EDIT_ACTION_VALUE", self::EDIT_ACTION_VALUE);
        $this->smarty->assign("EDITOR_THEME", Utils::getCodeEditorTheme());
        $this->smarty->assign("EDITOR_MODE", Utils::getCodeEditorMode());
        if (!empty($pid)) {
            $programController = new ProgramDetailsController();
            $programInfo = $programController->getProgramListById($pid);
            $storedFileName = $programInfo[ProgramDetails_DBTable::STORED_FILE_NAME];
            $category = $programInfo[ProgramDetails_DBTable::FK_CATEGORY_ID];
            $language = $programInfo[ProgramDetails_DBTable::FK_LANGUAGE_ID];
            $srcFile = Configuration::get(Configuration::CODE_BASE_DIR).$language.'/'.$category.'/'.$storedFileName;
            $srcCode = file_get_contents($srcFile);
            $this->smarty->assign("SELECTED_CATEGORY", $programInfo[ProgramDetails_DBTable::FK_CATEGORY_ID]);
            $this->smarty->assign("SELECTED_LANGUAGE", $programInfo[ProgramDetails_DBTable::FK_LANGUAGE_ID]);
            $this->smarty->assign("SELECTED_LEVEL", $programInfo[ProgramDetails_DBTable::LEVEL]);
            $this->smarty->assign("SELECTED_TITLE", $programInfo[ProgramDetails_DBTable::TITLE]);
            $this->smarty->assign("SELECTED_FILENAME", $programInfo[ProgramDetails_DBTable::ACTUAL_FILE_NAME]);
            $this->smarty->assign("SELECTED_DESCRIPTION", $programInfo[ProgramDetails_DBTable::DESCRIPTION]);
            $this->smarty->assign("SELECTED_VERIFIED", $programInfo[ProgramDetails_DBTable::IS_VERIFIED]);
            $this->smarty->assign("SELECTED_SOURCE_CODE", htmlentities($srcCode));
            $this->smarty->assign("IS_UPDATE_REQ", self::IS_UPDATE_VALUE);
            $this->smarty->assign("PROGRAM_CURRENT_ID", $programInfo[ProgramDetails_DBTable::PROGRAM_ID]);
        }
        $this->smarty->display('string: '. Display::render(self::MODULE_KEY));
    }

    private function isAjaxRequest($formParams) {
        if (!empty($formParams[self::EDIT_ACTION_NAME])) {
            return ($formParams[self::EDIT_ACTION_NAME] === self::EDIT_ACTION_VALUE);
        }
        return false;
    }

    private function isUpdateRequest($formParams) {
        $hasUpdateValue = ($formParams['isupdate'] === self::IS_UPDATE_VALUE);
        $hasProgramID = is_numeric($formParams['programid']);
        return ($hasProgramID && $hasUpdateValue);
    }

    public function saveFileOnDisk($fileDir, $fileName, $contents) {
        if (!is_dir($fileDir)) {
            try {
                mkdir($fileDir, 0777, true);
            } catch (Exception $e) {
                Logger::getLogger()->LogFatal('Exception: ' . $e->getMessage());
            }
        }

        if (is_writable($fileDir)) {
            $filePath = $fileDir."/".$fileName;
            return Utils::createNewFile($filePath, $contents);
        } else {
            Logger::getLogger()->LogFatal("Folder $fileDir is not writable");
            return false;
        }
    }
}