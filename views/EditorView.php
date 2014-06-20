<?php

class EditorView extends AbstractView {

    protected $templateMap = array(
        'EDITOR' => 'editor.htpl'
    );

    protected $currModule = 'editor';

    public function display() {
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $pid = $this->getBean()['pid'];
                $this->displayCodeEditor($pid);
            }
        }
    }

    private function displayCodeEditor($pid = false) {
        $categoryList = (new CategoryController())->getCategoryList();
        $languageList = (new LanguageController())->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("LEVEL_LIST", array('Easy', 'Average', 'Difficult'));
        $this->smarty->assign("EDIT_ACTION_NAME", EditorController::EDIT_ACTION_NAME);
        $this->smarty->assign("EDIT_ACTION_VALUE", EditorController::EDIT_ACTION_VALUE);
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
            $this->smarty->assign("IS_UPDATE_REQ", EditorController::IS_UPDATE_VALUE);
            $this->smarty->assign("PROGRAM_CURRENT_ID", $programInfo[ProgramDetails_DBTable::PROGRAM_ID]);
        }
        $this->render($this->currModule);
    }
}