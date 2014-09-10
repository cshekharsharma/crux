<?php

class ExecuteView extends AbstractView {

    protected $templateMap = array(
        'EXECUTE' => 'execute.htpl'
    );

    protected $currModule = 'execute';

    public function display() {
        chdir('../../../../');
        if (!empty($this->viewName)) {
            $bean = $this->getBean();
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                if ($bean['code'] === Constants::SUCCESS_RESPONSE) {
                    $this->smarty->assign('CMD_MSG', './a.out');
                } elseif ($bean['code'] === Constants::FAILURE_RESPONSE) {
                    $fileName = explode("\n", $bean['msg']);
                    $fileName = explode(":", $fileName[0]);
                    $fileName = $fileName[0];
                    $this->smarty->assign('CMD_MSG', 'gcc ' . $fileName);
                }
                $this->smarty->assign('MSG', $bean['msg']);
                $this->render($this->currModule);
            }
        }
    }
}