<?php

class UploadView extends AbstractView {

    protected $templateMap = array(
        'UPLOAD' => 'upload.htpl'
    );

    protected $currModule = 'upload';

    public function display() {
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displayUploadInterface();
            }
        }
    }

    public function displayUploadInterface() {
        $categoryObj = new CategoryController();
        $languageObj = new LanguageController();
        $categoryList = $categoryObj->getCategoryList();
        $languageList = $languageObj->getLanguageList();
        $this->smarty->assign("CATEGORY_LIST", $categoryList);
        $this->smarty->assign("LANGUAGE_LIST", $languageList);
        $this->smarty->assign("FILE_UPLOAD_ACTION_VALUE", UploadController::FILE_UPLOAD_ACTION_VALUE);
        $this->render($this->currModule);
    }

}