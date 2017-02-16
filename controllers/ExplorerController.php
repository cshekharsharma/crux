<?php
/**
 * Controller class for Explorer module
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class ExplorerController extends AbstractController {

    const MODULE_KEY = 'explorer';

    const DELETE_REQ_KEY = 'isdelete';
    const DELETE_REQ_VAL = 'deleteCode_byajax_EJASSJSDSG';

    public function __construct() {
        parent::__construct();
        $this->model = new ExplorerModel();
        $this->view = new ExplorerView();
    }

    /**
     * @see AbstractController::run()
     */
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
        }
    }

    /**
     * Prepare readonly source code explore UI
     *
     * @param string $lang
     * @param string $category
     * @param int    $pid
     */
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

    /**
     * Prepare bean to be passed in ExplorerView
     *
     * @param string $sourceCode
     * @param string $programDetails
     */
    private function prepareBean($sourceCode, $programDetails) {
        $array = array(
            'programDetails' => $programDetails,
            'sourceCode'    => $sourceCode,
            'sourceStats'   =>  $this->getSourceStats($sourceCode),
        );
        $this->setBean($array);
    }

    /**
     * Get source code of requested PID
     *
     * @param array $programDetails
     * @return string $fileContents
     */
    private function getSourceCode(array $programDetails) {
        $filePath = Configuration::get(Configuration::CODE_BASE_DIR);
        $filePath .= $programDetails[ProgramDetails_DBTable::FK_LANGUAGE_ID].'/';
        $filePath .= $programDetails[ProgramDetails_DBTable::FK_CATEGORY_ID].'/';
        $filePath .= $programDetails[ProgramDetails_DBTable::STORED_FILE_NAME];
        $fileContents = @file_get_contents($filePath);
        return $fileContents;
    }

    /**
     * Provides additional stats about given program
     *
     * @param string $sourceCode
     * @return array
     */
    private function getSourceStats($sourceCode) {
        return array(
            'lineCount' => substr_count($sourceCode, PHP_EOL) + 1,
            'wordCount' => str_word_count($sourceCode),
            'charCount' => strlen($sourceCode),
            'fileSize' => round((strlen($sourceCode) / 1024), 3)
        );
    }

    /**
     * Checks if its a delete request for program
     *
     * @param array $uriParams
     * @param array $formParams
     * @return boolean
     */
    private function isDeleteRequest (array $uriParams, array $formParams) {
        if ($formParams[self::DELETE_REQ_KEY] == self::DELETE_REQ_VAL) {
            return ($uriParams[Constants::INPUT_PARAM_CATE] === 'delete');
        }
        return false;
    }

    /**
     * Delete program from system for given PID
     *
     * @param int $pid
     */
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