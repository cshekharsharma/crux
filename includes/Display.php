<?php
/**
 * Class for maanaging views, template rendering and display
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since May 11, 2014
 */
class Display {

    public static $smarty;

    private static $TEMPLATE_LIST = array(
        'STATS' => 'Stats/tpls/showStats.htpl',
        'INDEX' => 'Index/tpls/Index.htpl',
        'UPLOAD' => 'Upload/tpls/uploadFile.htpl',
        'SEARCH' => 'Search/tpls/searchResults.htpl',
        'EDITOR' => 'Editor/tpls/editor.htpl',
        'EXPLORER' => 'Explorer/tpls/displayCode.htpl',
        'AUTH_LOGIN' => 'Auth/tpls/login.htpl',
        'CMS_CHPWD' => 'Content/tpls/changePassword.htpl',
        'NO_ITEM_FOUND' => 'Errors/tpls/noItemFound.htpl',
        'CMS_USERPREF' => 'Content/tpls/userPreference.tpl',
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

    /**
     * Fetches appropriate template by given template key and returns its content
     * 
     * @param string $templateKey
     * @return string
     */
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

    /**
     * Method for executing all the pre-display activities
     */
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