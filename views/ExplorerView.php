<?php

class ExplorerView extends AbstractView {

    protected $templateMap = array(
        'EXPLORER' => 'displayCode.htpl'
    );

    protected $currModule = 'explorer';

    public function display() {
        $bean = $this->getBean();
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displayCodeExplorer(
                    $bean['programDetails'],
                    $bean['sourceCode'],
                    $bean['sourceStats']
                );
            }
        }  else {
            $this->render(null);
        }
    }

    private function displayCodeExplorer($programDetails, $sourceCode, $sourceStats) {
        $this->smarty->assign("PROGRAM_DETAILS", $programDetails);
        $this->smarty->assign("EDITOR_MODE", Utils::getCodeEditorMode($programDetails));
        $this->smarty->assign("EDITOR_THEME", Utils::getCodeEditorTheme());
        $this->smarty->assign("SOURCE_CODE", htmlentities($sourceCode));
        $this->smarty->assign("SOURCE_STATS", $sourceStats);
        $this->smarty->assign("DELETE_REQ_KEY", ExplorerController::DELETE_REQ_KEY);
        $this->smarty->assign("DELETE_REQ_VAL", ExplorerController::DELETE_REQ_VAL);
        $this->render($this->currModule);
    }
}