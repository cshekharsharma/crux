<?php
/**
 * Controller class for Content module.
 * This modules provide dynamic markup content when requested from client
 * 
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class ContentController extends AbstractController {

    const MODULE_KEY = "content";

    const CMS_CHPWD = 'CMS_CHPWD';

    private $actionMapping = array(
        'changePassword' => 'getChangePasswordUI',
        'userPreferences' => 'getUserPreferenceUI'
    );

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $content = '';
        $uriParams = $resource->getParams();
        $contentKey = $uriParams[Constants::INPUT_PARAM_ACTION];
        if (!empty($contentKey)) {
            $this->callContentAction($contentKey);
        }
        $this->getErrorMsg();
    }

    /**
     * Call appropriate method for providing requested markup content
     * 
     * @param string $contentKey
     * @return boolean
     */
    private function callContentAction($contentKey) {
        $content = '';
        if (!empty($contentKey)) {
            $actionName = $this->actionMapping[$contentKey];
            if (method_exists($this, $actionName)) {
                $this->$actionName();
            } else {
                return false;
            }
        }
        return false;
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
        $content = $this->smarty->fetch('string:'.Display::render('CMS_CHPWD'));
        Response::sendResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }
    
    /**
     * Returms markup for user preference ui
     * 
     * @return string
     */
    public function getUserPreferenceUI() {
        $this->smarty->assign('EDITOR_THEME_LIST', Utils::getAceEditorThemes());
        $content = $this->smarty->fetch('string:'.Display::render('CMS_USERPREF'));
        Response::sendResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }
}