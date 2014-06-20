<?php

class ContentView extends AbstractView {

    protected $templateMap = array(
        'CHPWD'    => 'changePassword.htpl',
        'USERPREF' => 'userPreference.htpl',
    );

    private $actionMapping = array(
        'changePassword' => 'getChangePasswordUI',
        'userPreferences' => 'getUserPreferenceUI'
    );

    protected $currModule = 'content';

    /**
     * @see AbstractView::display()
     */
    public function display() {
        if (!empty($this->viewName)) {
            $actionName = $this->actionMapping[$this->viewName];
            if (method_exists($this, $actionName)) {
                $this->$actionName();
            } else {
                $this->getErrorMsg();
            }
        } else {
            $this->getErrorMsg();
        }
    }

    /**
     * Return error messsage if content could not be returned
     *
     * @return string
     */
    private function getErrorMsg() {
        Logger::getLogger()->LogError('ContentController: No content key provided');
        Response::sendResponse(Constants::FAILURE_RESPONSE, Messages::ERROR_SOMETHING_WRONG);
    }

    /**
     * Returns change password ui
     *
     * @return string
     */
    public function getChangePasswordUI() {
        $this->smarty->assign('CHPWD_ACTION_VALUE', AuthController::CHPWD_ACTION_VALUE);
        $content = $this->render('CHPWD', true);
        Response::sendResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }

    /**
     * Returms markup for user preference ui
     *
     * @return string
     */
    public function getUserPreferenceUI() {
        $this->smarty->assign('EDITOR_THEME_LIST', Utils::getAceEditorThemes());
        $content = $this->render('USERPREF', true);
        Response::sendResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }
}