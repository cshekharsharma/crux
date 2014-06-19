<?php

class ExplorerView extends AbstractView {

    protected $templateMap = array(
        'EXPLORER' => 'displayCode.htpl'
    );

    protected $currModule = 'explorer';

    public function display() {
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displayProgramList($this->getBean());
            }
        }
    }
}