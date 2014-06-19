<?php

class IndexView extends AbstractView {

    protected $templateMap = array(
        'INDEX' => 'Index.htpl'
    );

    protected $currModule = 'index';

    public function display() {
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === 'INDEX') {
                $this->displayProgramList($this->getBean());
            }
        }
    }

    private function displayProgramList($programList) {
        if (!empty($programList)) {
            foreach ($programList as $key => $programData) {
                $descKey = ProgramDetails_DBTable::DESCRIPTION;
                $desc = $programData[$descKey];
                $parsedDesc = Utils::createLinks($desc);
                $programList[$key][$descKey] = $parsedDesc;
            }
            $this->smarty->assign("PROGRAM_LIST", $programList);
            $this->render(IndexController::MODULE_KEY);
        } else {
            if (IndexController::$isHomePage) {
                $this->render("EMPTY_CODEBASE");
            } else {
                $this->render("NO_ITEM_FOUND");
            }
        }
    }
}