<?php

class EditorView extends AbstractView {

    protected $templateMap = array(
        'EDITOR' => 'editor.htpl'
    );

    protected $currModule = 'editor';

    public function display() {
        if (!empty($this->view)) {
            if (strtoupper($this->view) === strtoupper($this->currModule)) {
                $this->displayProgramList(self::getBean());
            }
        }
    }
}