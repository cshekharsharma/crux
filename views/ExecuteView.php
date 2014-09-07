<?php

class ExecuteView extends AbstractView {

    protected $templateMap = array(
        'EXECUTE' => 'exec.htpl'
    );

    protected $currModule = 'execute';

    public function display() {
        chdir('../../../../');
        if (!empty($this->viewName)) {
            $bean = $this->getBean();
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->smarty->assign('MSG', $bean['message']);
                $this->render($this->currModule);
            }
        }
    }
}