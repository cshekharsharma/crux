<?php

class EditorView extends AbstractView {

    protected $templateMap = array(
        'EDITOR' => 'editor.htpl'
    );

    protected $currModule = 'editor';

    public function display() {
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displayProgramList(self::getBean());
            }
        }
    }
}