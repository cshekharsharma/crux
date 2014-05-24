<?php

class UploadController extends AbstractController {

    const MODULE_KEY = "upload";

    const FILE_UPLOAD_FILED_NAME = "uploadfile";
    const FILE_UPLOAD_ACTION_NAME = "file-upload-action-name";
    const FILE_UPLOAD_ACTION_VALUE = "fileupload_byajax_AXIFJSBDSSID";

    private $allowedExts;

    public function __construct() {
        parent::__construct();
        $this->allowedExts = array(
            'c', 'cpp', 'java', 'py', 'php', 'cs', 'js', 'xml', 'json', 'rb', 'scala'
        );
    }

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!empty($formParams[self::FILE_UPLOAD_ACTION_NAME]) &&
            $formParams[self::FILE_UPLOAD_ACTION_NAME] == self::FILE_UPLOAD_ACTION_VALUE) {
            $this->uploadFile($formParams);
        } else {
            $this->displayUploadInterface();
        }
    }

    public function displayUploadInterface() {
        $categoryObj = new CategoryController();
        $languageObj = new LanguageController();
        $categoryList = $categoryObj->getCategoryList();
        $languageList = $languageObj->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("FILE_UPLOAD_ACTION_VALUE", self::FILE_UPLOAD_ACTION_VALUE);
        echo $this->smarty->fetch("string:".Display::render(self::MODULE_KEY));
    }

    public function uploadFile($formParams) {
        $category = $formParams['category_id'];
        $language = $formParams['language_id'];
        if (!empty($_FILES)) {
            if ($_FILES[self::FILE_UPLOAD_FILED_NAME]["error"] > 0) {
                $this->logErrorAndRedirect($_FILES[self::FILE_UPLOAD_FILED_NAME]["error"]);
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
                            $this->logErrorAndRedirect("File could not be uploaded, retry", Error::ERR_TYPE_DISPLAY);
                        } else {
                            $authUserData = Session::get(Session::SESS_USER_DETAILS);
                            $authUserId = $authUserData[Users_DBTable::USER_ID];
                            $formParams['actual_file_name'] = $originalFileName;
                            $formParams['stored_file_name'] = $newFileName;
                            $formParams['created_by'] = $authUserId;
                            if ($this->insertProgramDescription($formParams)) {
                                Response::sendResponse(Constants::SUCCESS_RESPONSE, 'Upload Successful');
                            } else {
                                Response::sendResponse(Constants::FAILURE_RESPONSE, 'Upload Failed');
                            }
                        }
                    } else {
                        Logger::getLogger()->LogError("Directory < $uploadFileDir > Doesn't Exist or Not Writable");
                        Response::sendResponse(Constants::FAILURE_RESPONSE, 'Upload Failed');
                    }
                } else {
                    Logger::getLogger()->LogError(Error::UPLOAD_INVALID_FILE_TYPE);
                    Response::sendResponse(Constants::FAILURE_RESPONSE, Error::UPLOAD_INVALID_FILE_TYPE);
                }
            }
        }
    }

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

    private function insertProgramDescription($params) {
        $query = "INSERT INTO ".ProgramDetails_DBTable::DB_TABLE_NAME." VALUES('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $bindParams = array(
            $params['program_title'], $params['language_id'], $params['category_id'], $params['actual_file_name'],
            $params['stored_file_name'], $params['level'], $params['program_description'], $params['is_verified'],
            Utils::getCurrentDatetime(), Utils::getCurrentDatetime(), $params['created_by'], 0);
        if (DBManager::executeQuery($query, $bindParams, false)) {
            return true;
        } else {
            return false;
        }
    }

    private function logErrorAndRedirect($msg, $type) {
        Logger::getLogger()->LogFatal($msg);

    }
}