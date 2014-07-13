<?php
/**
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class UploadController extends AbstractController {

    const MODULE_KEY = "upload";

    const FILE_UPLOAD_FILED_NAME = "uploadfile";
    const FILE_UPLOAD_ACTION_NAME = "file-upload-action-name";
    const FILE_UPLOAD_ACTION_VALUE = "fileupload_byajax_AXIFJSBDSSID";

    private $allowedExts;

    public function __construct() {
        parent::__construct();
        $this->model = new UploadModel();
        $this->view  = new UploadView();
        $this->allowedExts = array(
            'c', 'cpp', 'java', 'py', 'php', 'cs', 'js',
            'xml', 'json', 'rb', 'scala', 'go'
        );
    }

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!empty($formParams[self::FILE_UPLOAD_ACTION_NAME]) &&
        $formParams[self::FILE_UPLOAD_ACTION_NAME] == self::FILE_UPLOAD_ACTION_VALUE) {
            $this->uploadFile($formParams);
        } else {
            $this->getView()->setViewName(self::MODULE_KEY)->display();
        }
    }

    /**
     * Upload file on system
     * 
     * @param array $formParams
     */
    public function uploadFile(array $formParams) {
        $category = $formParams['category_id'];
        $language = $formParams['language_id'];
        if (!empty($_FILES)) {
            if ($_FILES[self::FILE_UPLOAD_FILED_NAME]["error"] > 0) {
                Logger::getLogger()->LogFatal('File upload failed, Errors found in $FILES array');
                Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_SOMETHING_WENT_WRONG);
            } else {
                $originalFileName = $_FILES[self::FILE_UPLOAD_FILED_NAME]["name"];
                $nameParts = explode(".", $originalFileName);
                $extension = end($nameParts);
                if (in_array($extension, $this->allowedExts)) {
                    $newFileName = Utils::getStoredFileName($originalFileName);
                    $uploadFileDir = Configuration::get(Configuration::CODE_BASE_DIR).$language.'/'.$category;
                    if ($this->checkAndCreateDir($uploadFileDir)) {
                        $uploadFileLocation = $uploadFileDir.'/'.$newFileName;
                        if (!move_uploaded_file($_FILES[self::FILE_UPLOAD_FILED_NAME]["tmp_name"], $uploadFileLocation)) {
                            Logger::getLogger()->LogFatal("File upload failed while trying move_uploaded_file()");
                            Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_SOMETHING_WENT_WRONG);
                        } else {
                            $authUserData = Session::get(Session::SESS_USER_DETAILS);
                            $authUserId = $authUserData[Users_DBTable::USER_ID];
                            $formParams['actual_file_name'] = $originalFileName;
                            $formParams['stored_file_name'] = $newFileName;
                            $formParams['created_by'] = $authUserId;
                            if ($this->getModel()->insertProgramDescription($formParams)) {
                                Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_UPDATE);
                            } else {
                                Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_OPERATION_FAILED);
                            }
                        }
                    } else {
                        Logger::getLogger()->LogError("Directory < $uploadFileDir > Doesn't Exist or Not Writable");
                        Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_OPERATION_FAILED);
                    }
                } else {
                    Logger::getLogger()->LogError(Error::UPLOAD_INVALID_FILE_TYPE);
                    Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::UPLOAD_INVALID_FILE_TYPE);
                }
            }
        }
    }

    /**
     * Check if directory exists.
     * If not then create new
     * 
     * @param string $dirPath
     * @return boolean
     */
    private function checkAndCreateDir($dirPath) {
        if (!is_dir($dirPath)) {
            try {
                return mkdir($dirPath, 0777, true);
            } catch (Exception $e) {
                Logger::getLogger()->LogFatal('Exception: ' . $e->getMessage());
            }
        } elseif (!is_writable($dirPath)) {
            return false;
        }
        return true;
    }

    /**
     * Log error in main log file and redirect to home page
     * 
     * @param string $msg
     * @param string $type
     */
    private function logErrorAndRedirect($msg, $type) {
        Logger::getLogger()->LogFatal($msg);
        RequestManager::redirect();
    }
}