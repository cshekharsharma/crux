<?php

class AuthView extends AbstractView {

    protected $templateMap = array(
        'AUTH_LOGIN' => 'login.htpl'
    );

    protected $view = null;
    
    protected $currModule = 'auth';
    
    public function displayForm() {
        if (!empty($this->view)) {
            if ($this->view === 'auth_login') {
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
    
    public function setView($view) {
        $this->view = $view;
    }
} 