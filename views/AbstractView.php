<?php
/**
 * Base view class for all the modules
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package views
 * @since Jun 11, 2014
 */
abstract class AbstractView extends Application {

    abstract public function display();

    protected $smarty = null;

    protected $viewName = null;

    protected $currModule = null;

    protected $templateMap = null;

    protected $htplDir = 'tpls/';

    protected $errorTemplateMap = array(
        'DEBUG_TRACE'    => 'debugTrace.htpl',
        'NO_ITEM_FOUND'  => 'noItemFound.htpl',
        'EMPTY_CODEBASE' => 'emptyCodebase.htpl'
    );


    public function __construct() {
        $this->smarty = Display::getSmarty();
    }

    public function setViewName($viewName) {
        $this->viewName = $viewName;
        return $this;
    }

    public function getTemplateName($key) {
        if (!empty($this->templateMap[$key])) {
            return $this->templateMap[$key];
        } else {
            Logger::getLogger()->LogError("No template found with Key: <$key>");
        }
        return false;
    }

    /**
     * Fetches appropriate template by given template key and returns its content
     *
     * @param string $templateKey
     * @return string
     */
    public function render($templateKey, $return = false) {
        $content = '';
        $this->prepareDisplay();
        $templateKey = strtoupper($templateKey);
        if ($this->isTemplateAvailable($templateKey)) {
            $content = $this->getTemplateMarkup($templateKey);
        } elseif ($this->isErrorTemplate($templateKey)) {
            $content = $this->getErrorTemaplateMarkup($templateKey);
        } else {
            $errorCode = Constants::ERROR_RESOURCE_NOT_FOUND;
            $content = $this->getErrorTemaplateMarkup($errorCode);
        }
        if ($return) {
            return $this->smarty->fetch('string: ' . $content);
        } else {
            $this->smarty->display('string: '. $content);
            return null;
        }
    }

    /**
     * Method for executing all the pre-display activities
     */
    private function prepareDisplay() {
        $searchSuggestions = Session::get(Session::SESS_SEARCH_SUGGESTIONS);
        if (empty($searchSuggestions)) {
            $search = new SearchController();
            $searchSuggestions = $search->getSearchSuggestions();
            Session::set(Session::SESS_SEARCH_SUGGESTIONS, $searchSuggestions);
        }
        
        $this->smarty->assign('APP_NAME', Constants::APP_NAME);
        $this->smarty->assign('APP_LOGO', Constants::APP_LOGO);
        $this->smarty->assign('APP_VERSION', Constants::APP_VERSION);
        $this->smarty->assign('CSRF_TOKEN_NAME', Constants::CSRF_TOKEN_NAME);
        $this->smarty->assign('CSRF_TOKEN_VALUE', RequestManager::getCsrfToken());
        $this->smarty->assign('SEARCH_SUGGESTIONS', $searchSuggestions);
        $this->smarty->assign('CHPWD_ACTION_VALUE', AuthController::CHPWD_ACTION_VALUE);
    }

    private function getTemplateDir () {
        return Constants::WEBROOT_DIR . ucfirst($this->currModule) . '/' . $this->htplDir;
    }

    private function getErrorTemplateDir() {
        return Constants::WEBROOT_DIR . ucfirst('errors') . '/' . $this->htplDir;
    }

    private function isErrorTemplate($key) {
        return !empty($this->errorTemplateMap[$key]);
    }
    
    private function isTemplateAvailable ($templateKey) {
        return (!empty($this->templateMap[$templateKey]));
    }

    private function getTemplateMarkup($templatePath) {
        $filePath = $this->getTemplateDir() . $this->templateMap[$templatePath];
        return @file_get_contents($filePath);
    }

    private function getErrorTemaplateMarkup($errorCode) {
        $filePath .= $this->getErrorTemplateDir() . $this->errorTemplateMap[$errorCode];
        return @file_get_contents($filePath);
    }
}