<?php

class EditorController extends BaseController {
    
    const MODULE_KEY = 'editor';
    
    public function run(Resource $resource) {
        $this->displayCodeEditor();
    }
    
    public function displayCodeEditor() {
        $this->smarty->display('string: '. Display::render('EDITOR'));
    }
}