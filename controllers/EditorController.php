<?php

class EditorController extends BaseController {

    const MODULE_KEY = 'editor';

    const EDIT_ACTION_NAME = 'edit_action_name';
    const EDIT_ACTION_VALUE = 'editCode_byajax_WBNFLZCAN';
    const IS_UPDATE_VALUE = "isupdate";

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!$this->isAjaxRequest($formParams)) {
            if (is_numeric($uriParams[Constants::INPUT_PARAM_PID])) {
                $this->displayCodeEditor($uriParams[Constants::INPUT_PARAM_PID]);
            } else {
                $this->displayCodeEditor();
            }
        } else {
            if (!$this->isUpdateRequest($formParams)) {
                $this->submitCode($formParams);
            } else {
                $this->updateCode($formParams);
            }
             
        }
    }

    private function submitCode($formParams) {
        $loggedInUser = Session::get(Session::SESS_USER_DETAILS);
        $storedFileName = Utils::getStoredFileName($formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME]);
        $lang = $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID];
        $cate = $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID];
        $fileDir = Configuration::get(Configuration::CODE_BASE_DIR).$lang."/".$cate;;
        $fileContents = $formParams['editorContents'];
        $currentDatetime = Utils::getCurrentDatetime();
        if ($this->saveFileOnDisk($fileDir, $storedFileName, $fileContents)) {
            $attribs = array(
                '',
                $formParams[ProgramDetails_DBTable::TITLE],
                $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID],
                $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID],
                $formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME],
                $storedFileName,
                $formParams[ProgramDetails_DBTable::LEVEL],
                $formParams[ProgramDetails_DBTable::DESCRIPTION],
                $formParams[ProgramDetails_DBTable::IS_VERIFIED],
                $currentDatetime,
                $currentDatetime,
                $loggedInUser[Users_DBTable::USER_ID],
                0
            );
            $query = "INSERT INTO ".ProgramDetails_DBTable::DB_TABLE_NAME." VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);";
            if (DBManager::executeQuery($query, $attribs, false)) {
                $detailArray = array(
                    'isUpdate' => self::IS_UPDATE_VALUE,
                    'programId' => $this->getLastInsertedRecord($loggedInUser[Users_DBTable::USER_ID], $currentDatetime, $storedFileName)
                );
                echo ServiceResponse::createServiceResponse(Constants::SUCCESS_RESPONSE, "Code Submitted", $detailArray);
            } else {
                echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, "Code Submission failed, Retry!", '');
            }
        } else {
            echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, "Code Submission failed, Retry", '');
        }
        exit();
    }

    private function updateCode($formParams) {
        $loggedInUser = Session::get(Session::SESS_USER_DETAILS);
        $storedFileName = Utils::getStoredFileName($formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME]);
        $lang = $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID];
        $cate = $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID];
        $fileDir = Configuration::get(Configuration::CODE_BASE_DIR).$lang."/".$cate;;
        $fileContents = $formParams['editorContents'];
        $currentDatetime = Utils::getCurrentDatetime();
        $pid = $formParams['programid'];
        $programController = new ProgramDetailsController();
        $prevProgramInfo = $programController->getProgramListById($pid);
        $fileToUnlink = Configuration::get(Configuration::CODE_BASE_DIR) .
        $prevProgramInfo[ProgramDetails_DBTable::FK_LANGUAGE_ID]."/".
        $prevProgramInfo[ProgramDetails_DBTable::FK_CATEGORY_ID]."/".
        $prevProgramInfo[ProgramDetails_DBTable::STORED_FILE_NAME];
        unlink($fileToUnlink);
        if ($this->saveFileOnDisk($fileDir, $storedFileName, $fileContents)) {
            $attribs = array(
                $formParams[ProgramDetails_DBTable::TITLE],
                $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID],
                $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID],
                $formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME],
                $storedFileName,
                $formParams[ProgramDetails_DBTable::LEVEL],
                $formParams[ProgramDetails_DBTable::DESCRIPTION],
                $formParams[ProgramDetails_DBTable::IS_VERIFIED],
                $currentDatetime,
                $loggedInUser[Users_DBTable::USER_ID],
                0
            );
            $query = "UPDATE ".ProgramDetails_DBTable::DB_TABLE_NAME." SET ";
            $query .= ProgramDetails_DBTable::TITLE ."=?,";
            $query .= ProgramDetails_DBTable::FK_LANGUAGE_ID ."=?,";
            $query .= ProgramDetails_DBTable::FK_CATEGORY_ID ."=?,";
            $query .= ProgramDetails_DBTable::ACTUAL_FILE_NAME ."=?,";
            $query .= ProgramDetails_DBTable::STORED_FILE_NAME ."=?,";
            $query .= ProgramDetails_DBTable::LEVEL ."=?,";
            $query .= ProgramDetails_DBTable::DESCRIPTION ."=?,";
            $query .= ProgramDetails_DBTable::IS_VERIFIED ."=?,";
            $query .= ProgramDetails_DBTable::UPDATED_ON ."=?,";
            $query .= ProgramDetails_DBTable::FK_CREATED_BY ."=?,";
            $query .= ProgramDetails_DBTable::IS_DELETED ."=? ";
            $query .= "WHERE ".ProgramDetails_DBTable::PROGRAM_ID."='".$pid."'";
            if (DBManager::executeQuery($query, $attribs, false)) {
                $detailArray = array(
                    'isUpdate' => self::IS_UPDATE_VALUE,
                    'programId' => $pid
                );
                echo ServiceResponse::createServiceResponse(Constants::SUCCESS_RESPONSE, "Code Successfully Updated", $detailArray);
            } else {
                echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, "Code Updation failed, Retry!", '');
            }
        } else {
            echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, "Code Updation failed, Retry", '');
        }
        exit();
    }

    private function displayCodeEditor($pid = false) {
        $categoryList = ResourceProvider::getControllerByResourceKey(CategoryController::MODULE_KEY)->getCategoryList();
        $languageList = ResourceProvider::getControllerByResourceKey(LanguageController::MODULE_KEY)->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("LEVEL_LIST", array('Easy', 'Average', 'Difficult'));
        $this->smarty->assign("EDIT_ACTION_NAME", self::EDIT_ACTION_NAME);
        $this->smarty->assign("EDIT_ACTION_VALUE", self::EDIT_ACTION_VALUE);
        $this->smarty->assign("EDITOR_THEME", Configuration::get(Configuration::CODE_EDITOR_THEME));
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
            $this->smarty->assign("SELECTED_SOURCE_CODE", htmlentities($srcCode));
            $this->smarty->assign("IS_UPDATE_REQ", self::IS_UPDATE_VALUE);
            $this->smarty->assign("PROGRAM_CURRENT_ID", $programInfo[ProgramDetails_DBTable::PROGRAM_ID]);
            $this->smarty->assign("EDITOR_MODE", Utils::getCodeEditorMode($programInfo));
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

    private function saveFileOnDisk($fileDir, $fileName, $contents) {
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

    private function getLastInsertedRecord($userId, $datetime, $filename) {
        $query = 'SELECT * FROM '.ProgramDetails_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= ProgramDetails_DBTable::STORED_FILE_NAME. " = '".$filename."' AND ";
        $query .= ProgramDetails_DBTable::CREATED_ON. " = '".$datetime."' AND ";
        $query .= ProgramDetails_DBTable::FK_CREATED_BY. " = '".$userId."' AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED. " = 0 LIMIT 1";
        $result = DBManager::executeQuery($query, array(), true);
        if ($result) {
            $row = current($result);
            return $row[ProgramDetails_DBTable::PROGRAM_ID];
        }
        return false;
    }
}