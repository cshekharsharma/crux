<?php

class ContentController extends BaseController {

    const MODULE_KEY = "content";
    const CONTENT_KEY = 'content_key';

    const CMS_CHPWD = 'CMS_CHPWD';

    private $actionMapping = array(
        'changePassword' => 'getChangePasswordUI',
        'userPreferences' => 'getUserPreferenceUI'
    );

    public function run(Resource $resource) {
        $content = '';
        $uriParams = $resource->getParams();
        $contentKey = $uriParams[self::CONTENT_KEY];
        if (!empty($contentKey)) {
            $content = $this->getMarkup($contentKey);
        }
        echo (!empty($content)) ? $content : $this->getErrorMsg();
    }

    private function getMarkup($contentKey) {
        $content = '';
        if (!empty($contentKey)) {
            $actionName = $this->actionMapping[$contentKey];
            if (method_exists($this, $actionName)) {
                $content = $this->$actionName();
            }
        }

        return $content;
    }

    private function getErrorMsg() {
        Logger::getLogger()->LogError('ContentController: No content key provided');
        return Response::createResponse(Constants::FAILURE_RESPONSE, Error::ERR_SOMETHING_WRONG, '');
    }

    public function getChangePasswordUI() {
        $this->smarty->assign('CHPWD_ACTION_VALUE', AuthController::CHPWD_ACTION_VALUE);
        $content = $this->smarty->fetch('string:'.Display::render('CMS_CHPWD'));
        return Response::createResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }
    
    public function getUserPreferenceUI() {
        $this->smarty->assign('EDITOR_THEME_LIST', Utils::getAceEditorThemes());
        $content = $this->smarty->fetch('string:'.Display::render('CMS_USERPREF'));
        return Response::createResponse(Constants::SUCCESS_RESPONSE, '', $content);
    }
}