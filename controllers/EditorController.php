<?php
/**
 * Controller class for Editor module
 * 
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package 
 * @since Jun 20, 2014
 */
class EditorController extends AbstractController {

    const MODULE_KEY = 'editor';

    const EDIT_ACTION_NAME = 'edit_action_name';
    const EDIT_ACTION_VALUE = 'editCode_byajax_WBNFLZCAN';
    const IS_UPDATE_VALUE = "isupdate";

    public function __construct() {
        parent::__construct();
        $this->model = new EditorModel();
        $this->view = new EditorView();
    }

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!$this->isEditRequest($formParams)) {
            if (is_numeric($uriParams[Constants::INPUT_PARAM_ACTION])) {
                $this->setBean(array('pid' => $uriParams[Constants::INPUT_PARAM_ACTION]));
            } else {
                $this->setBean(array('pid' => null));
            }
            $this->getView()->setViewName(self::MODULE_KEY)->display();
        } else {
            if (!$this->isUpdateRequest($formParams)) {
                $this->getModel()->submitCode($formParams);
            } else {
                $this->getModel()->updateCode($formParams);
            }
             
        }
    }

    /**
     * Checks if its a valid edit request
     * 
     * @param array $formParams
     * @return boolean
     */
    private function isEditRequest(array $formParams) {
        if (!empty($formParams[self::EDIT_ACTION_NAME])) {
            return ($formParams[self::EDIT_ACTION_NAME] === self::EDIT_ACTION_VALUE);
        }
        return false;
    }

    /**
     * Checks if its a valid update request
     * 
     * @param array $formParams
     * @return boolean
     */
    private function isUpdateRequest(array $formParams) {
        $hasUpdateValue = ($formParams['isupdate'] === self::IS_UPDATE_VALUE);
        $hasProgramID = is_numeric($formParams['programid']);
        return ($hasProgramID && $hasUpdateValue);
    }

    /**
     * Write source code in file and saves on server file
     * 
     * @param string $fileDir
     * @param string $fileName
     * @param string $contents
     * @return boolean
     */
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