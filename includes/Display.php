<?php

class Display {

    public static $smarty;

    private static $TEMPLATE_LIST = array(
        'INDEX' => 'Index/tpls/Index.htpl',
        'UPLOAD' => 'Upload/tpls/uploadFile.htpl',
        'SEARCH' => 'Search/tpls/searchResults.htpl',
        'EDITOR' => 'Editor/tpls/editor.htpl',
        'STATS' => 'Stats/tpls/showStats.htpl',
        'EXPLORER' => 'Explorer/tpls/displayCode.htpl',
        'AUTH_LOGIN' => 'Auth/tpls/login.htpl',
        'NO_ITEM_FOUND' => 'Errors/tpls/noItemFound.htpl',
        'EMPTY_CODEBASE' => 'Errors/tpls/emptyCodebase.htpl',
    );

    private static $ERROR_TEMPLATE_LIST = array(
        '404' => 'Errors/tpls/noItemFound.htpl'
    );

    private static function getTemplatePath ($key) {
        return Constants::VIEW_DIR . self::$TEMPLATE_LIST[$key];
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
        return file_get_contents(Constants::VIEW_DIR . self::$ERROR_TEMPLATE_LIST[$errorCode]);
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