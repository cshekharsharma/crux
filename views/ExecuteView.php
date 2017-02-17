<?php

class ExecuteView extends AbstractView {

    protected $templateMap = array(
        'EXECUTE' => 'execute.htpl'
    );

    protected $currModule = 'execute';

    public function display() {
        if (!empty($this->viewName)) {
            $bean = $this->getBean();
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->smarty->assign('CMD_MSG', $bean['msg']['CMD']);
                $this->smarty->assign('MSG', $bean['msg']['OUTPUT']);
                $this->render($this->currModule);
            }
        }
    }
}