<?php

class EditorModel extends AbstractModel {

    public function updateCode($formParams) {
        $controller = new EditorController();
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
        if ($controller->saveFileOnDisk($fileDir, $storedFileName, $fileContents)) {
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
                    'isUpdate' => EditorController::IS_UPDATE_VALUE,
                    'programId' => $pid
                );
                echo Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_CODE_UPDATED, $detailArray);
            } else {
                echo Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_CODE_UPDATION_FAILED);
            }
        } else {
            echo Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_CODE_UPDATION_FAILED);
        }
        exit();
    }


    public function submitCode($formParams) {
        $controller = new EditorController();
        $loggedInUser = Session::get(Session::SESS_USER_DETAILS);
        $loggedInUserId = $loggedInUser[Users_DBTable::USER_ID];
        $storedFileName = Utils::getStoredFileName($formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME]);
        $lang = $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID];
        $cate = $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID];
        $fileDir = Configuration::get(Configuration::CODE_BASE_DIR).$lang."/".$cate;;
        $fileContents = $formParams['editorContents'];
        $currentDatetime = Utils::getCurrentDatetime();
        if ($controller->saveFileOnDisk($fileDir, $storedFileName, $fileContents)) {
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
                    'isUpdate' => EditorController::IS_UPDATE_VALUE,
                    'programId' => $this->getLastInsertedRecord($loggedInUserId, $currentDatetime, $storedFileName)
                );
                Response::sendResponse(Constants::SUCCESS_RESPONSE, Messages::SUCCESS_CODE_SUBMITTED, $detailArray);
            } else {
                Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_CODE_SUBMISSION_FAILED);
            }
        } else {
            Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_CODE_SUBMISSION_FAILED);
        }
    }

    public function getLastInsertedRecord($userId, $datetime, $filename) {
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