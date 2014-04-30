<?php

class EditorController extends BaseController {

    const MODULE_KEY = 'editor';
    const PID = 'pid';
    const EDIT_ACTION_NAME = 'edit_action_name';
    const EDIT_ACTION_VALUE = 'editCode_byajax_WBNFLZCAN';

    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $formParams = RequestManager::getAllParams();
        if (!$this->isAjaxRequest($formParams)) {
            $this->displayCodeEditor();
        } else {
            $this->submitCode($formParams);
        }
    }

    private function submitCode($formParams) {
        $loggedInUser = Session::get(Session::SESS_USER_DETAILS);
        $attribs = array(
            '',
            $formParams[ProgramDetails_DBTable::TITLE],
            $formParams[ProgramDetails_DBTable::FK_LANGUAGE_ID],
            $formParams[ProgramDetails_DBTable::FK_CATEGORY_ID],
            $formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME],
            Utils::getStoredFileName($formParams[ProgramDetails_DBTable::ACTUAL_FILE_NAME]),
            $formParams[ProgramDetails_DBTable::LEVEL],
            $formParams[ProgramDetails_DBTable::DESCRIPTION],
            $formParams[ProgramDetails_DBTable::IS_VERIFIED],
            Utils::getCurrentDatetime(),
            $loggedInUser[Users_DBTable::USER_ID],
            0
        );
        $query = "INSERT INTO ".ProgramDetails_DBTable::DB_TABLE_NAME." VALUES (?,?,?,?,?,?,?,?,?,?,?,?);";
        if (DBManager::executeQuery($query, $attribs, false)) {
            echo ServiceResponse::createServiceResponse(Constants::SUCCESS_RESPONSE, "Code Submitted", '');
        } else {
            echo ServiceResponse::createServiceResponse(Constants::FAILURE_RESPONSE, "Code Submission failed, Retry", '');
        }
        exit();
    }

    private function displayCodeEditor() {
        $categoryList = ResourceProvider::getControllerByResourceKey(CategoryController::MODULE_KEY)->getCategoryList();
        $languageList = ResourceProvider::getControllerByResourceKey(LanguageController::MODULE_KEY)->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("EDIT_ACTION_NAME", self::EDIT_ACTION_NAME);
        $this->smarty->assign("EDIT_ACTION_VALUE", self::EDIT_ACTION_VALUE);
        $this->smarty->assign("EDITOR_THEME", Configuration::get('CODE_EDITOR_THEME'));
        $this->smarty->display('string: '. Display::render('EDITOR'));
    }

    private function isAjaxRequest($formParams) {
        if (!empty($formParams[self::EDIT_ACTION_NAME])) {
            if ($formParams[self::EDIT_ACTION_NAME] === self::EDIT_ACTION_VALUE) {
                return true;
            }
        }
        return false;
    }
}