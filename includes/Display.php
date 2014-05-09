<?php

class Display {

    public static $smarty;

    private static $TEMPLATE_LIST = array(
        'AUTH_LOGIN' => 'ui/Auth/tpls/login.htpl',
        'INDEX' => 'ui/Index/tpls/Index.htpl',
        'DISPLAY_SOURCE_CODE' => 'ui/Explorer/tpls/displayCode.htpl',
        'UPLOAD_FILE' => 'ui/Upload/tpls/uploadFile.htpl',
        'NO_ITEM_FOUND' => 'ui/Errors/tpls/noItemFound.htpl',
        'EMPTY_CODEBASE' => 'ui/Errors/tpls/emptyCodebase.htpl',
        'SEARCH' => 'ui/Search/tpls/searchResults.htpl',
        'EDITOR' => 'ui/Editor/tpls/editor.htpl',
        'STATS' => 'ui/Stats/tpls/showStats.htpl'
    );

    private static $ERROR_TEMPLATE_LIST = array(
        '404' => 'ui/Errors/tpls/noItemFound.htpl'
    );

    private static function getTemplatePath ($key) {
        return self::$TEMPLATE_LIST[$key];
    }

    private static function getTemplateMarkup($templatePath) {
        return file_get_contents($templatePath);
    }

    private static function isTemplateAvailable ($templateKey) {
        return (!empty(self::$TEMPLATE_LIST[$templateKey]));
    }

    public static function render($templateKey) {
        self::prepareDisplay();
        $templateKey = strtoupper($templateKey);
        if (self::isTemplateAvailable($templateKey)) {
            $templatePath = self::getTemplatePath($templateKey);
            return self::getTemplateMarkup($templatePath);
        } else {
            $errorCode = Error::ERR_RESOURCE_NOT_FOUND;
            return self::getErrorTemaplateMarkup($errorCode);
        }
    }

    private static function getErrorTemaplateMarkup($errorCode) {
        return file_get_contents(self::$ERROR_TEMPLATE_LIST[$errorCode]);
    }

    private static function prepareDisplay() {
        $smarty = Utils::getSmarty();
        $searchSuggestions = Session::get(Session::SESS_SEARCH_SUGGESTIONS);
        if (empty($searchSuggestions)) {
            $searchSuggestions = SearchController::getSearchSuggestions();
            Session::set(Session::SESS_SEARCH_SUGGESTIONS, $searchSuggestions);
        }
        $smarty->assign('APP_NAME', Constants::APP_NAME);
        $smarty->assign('APP_VERSION', Constants::APP_VERSION);
        $smarty->assign('SEARCH_SUGGESTIONS', $searchSuggestions);
        $smarty->assign('CHPWD_ACTION_VALUE', AuthController::CHPWD_ACTION_VALUE);
    }
}