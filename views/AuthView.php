<?php

class AuthView extends AbstractView {

    protected $templateMap = array(
        'AUTH_LOGIN' => 'login.htpl'
    );

    protected $currModule = 'auth';
    
    public function display() {
        if (!empty($this->view)) {
            if ($this->view === 'AUTH_LOGIN') {
                $this->displayLoginForm();
            }
        } else {
            RequestManager::redirect();
        }
    }

    /**
     * Display login form
     */
    private function displayLoginForm() {
        if (!AuthController::isLoggedIn()) {
            $this->smarty->assign('LOGIN_ACTION_VALUE', AuthController::LOGIN_ACTION_VALUE);
            $this->smarty->display("string:".$this->render(AuthController::AUTH_LOGIN_KEY));
        } else {
            RequestManager::redirect();
        }
    }
} 