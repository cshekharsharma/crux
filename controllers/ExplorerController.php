<?php

class ExplorerController extends AbstractController {

    const MODULE_KEY = 'explorer';

    const DELETE_REQ_KEY = 'isdelete';
    const DELETE_REQ_VAL = 'deleteCode_byajax_EJASSJSDSG';

    public function __construct() {
        parent::__construct();
        $this->model = new ExplorerModel();
        $this->view = new ExplorerView();
    }

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if ($this->isDeleteRequest($uriParams, $formParams)) {
            $pid = $uriParams[Constants::INPUT_PARAM_PID];
            if (!empty($pid) && is_numeric($pid)) {
                $this->deleteSource($pid);
            }
        } else {
            $pid = $uriParams[Constants::INPUT_PARAM_PID];
            $language = $uriParams[Constants::INPUT_PARAM_LANG];
            $category = $uriParams[Constants::INPUT_PARAM_CATE];
            $this->exploreCode($language, $category, $pid);
            Display::render(strtoupper($resource->getKey()));
        }
    }

    public function exploreCode($lang, $category, $pid) {
        $programDetails = $this->getModel()->getSourceDetails($lang, $category, $pid);
        if (!empty($programDetails)) {
            $sourceCode = $this->getSourceCode($programDetails);
            $this->prepareBean($sourceCode, $programDetails);
            $this->getView()->setViewName(self::MODULE_KEY)->display();
        } else {
            $this->getView()->setViewName(null)->display();
        }
    }

    private function prepareBean($sourceCode, $programDetails) {
        $array = array(
            'programDetails' => $programDetails,
            'sourceCode'    => $sourceCode,
            'sourceStats'   =>  $this->getSourceStats($sourceCode),
        );
        $this->setBean($array);
    }

    private function getSourceCode($programDetails) {
        $filePath = Configuration::get(Configuration::CODE_BASE_DIR);
        $filePath .= $programDetails[ProgramDetails_DBTable::FK_LANGUAGE_ID].'/';
        $filePath .= $programDetails[ProgramDetails_DBTable::FK_CATEGORY_ID].'/';
        $filePath .= $programDetails[ProgramDetails_DBTable::STORED_FILE_NAME];
        $fileContents = @file_get_contents($filePath);
        return $fileContents;
    }

    private function getProcessedTemplate($programDetails, $sourceCode) {
        $rawContents = Display::render(self::MODULE_KEY);
        $this->smarty->assign("PROGRAM_DETAILS", $programDetails);
        $this->smarty->assign("EDITOR_MODE", Utils::getCodeEditorMode($programDetails));
        $this->smarty->assign("EDITOR_THEME", Utils::getCodeEditorTheme());
        $this->smarty->assign("SOURCE_CODE", htmlentities($sourceCode));
        $this->smarty->assign("SOURCE_STATS", $this->getSourceStats($sourceCode));
        $this->smarty->assign("DELETE_REQ_KEY", self::DELETE_REQ_KEY);
        $this->smarty->assign("DELETE_REQ_VAL", self::DELETE_REQ_VAL);
        return $this->smarty->fetch('string:'.$rawContents);
    }

    private function getSourceStats($sourceCode) {
        return array(
            'lineCount' => substr_count($sourceCode, PHP_EOL) + 1,
            'wordCount' => str_word_count($sourceCode),
            'charCount' => strlen($sourceCode),
            'fileSize' => round((strlen($sourceCode) / 1024), 3)
        );
    }

    private function isDeleteRequest ($uriParams, $formParams) {
        if ($formParams[self::DELETE_REQ_KEY] == self::DELETE_REQ_VAL) {
            return ($uriParams[Constants::INPUT_PARAM_CATE] === 'delete');
        }
        return false;
    }

    private function deleteSource($pid) {
        $isDeleted = false;
        $programController = new ProgramDetailsController();
        if ($programController->deleteProgram($pid)) {
            Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_FILE_DELETION);
            $isDeleted = true;
        } else {
            Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::ERROR_FILE_DELETION);
        }
    }
}