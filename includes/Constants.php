<?php

/**
 * Application Constant Class
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @since 07 may, 2014
 */
final class Constants {

    const APP_NAME = 'Crux';
    const APP_LOGO = '&lt; Crux /&gt;';
    const APP_VERSION = '1.0.0';
    const SITE_URL = 'crux.io';

    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';

    const DATA_DIR = 'data/';
    const VIEW_DIR = 'views/';
    const CACHE_DIR = 'cache/';
    const MODEL_DIR = 'models/';
    const HELPER_DIR = 'helpers/';
    const LIBRARY_DIR = 'library/';
    const WEBROOT_DIR = 'webroot/';
    const INCLUDE_DIR = 'includes/';
    const DB_TABLE_DIR = 'dbtables/';
    const CONTROLLER_DIR = 'controllers/';
    const HELPER_CLASSES_DIR = 'helpers/classes/';
    const HELPER_SCRIPTS_DIR = 'helpers/scripts/';

    const STATS_URI_KEY = 'stats';
    const INDEX_URI_KEY = 'index';
    const SEARCH_URI_KEY = 'search';
    const EDITOR_URI_KEY = 'editor';
    const DELETE_URI_KEY = 'delete';
    const UPLOAD_URI_KEY = 'upload';
    const CONTENT_URI_KEY = 'content';
    const EXPLORER_URI_KEY = 'explorer';
    const DOWNLOAD_URI_KEY = 'download';
    const USER_PREF_URI_KEY = 'userPreferences';

    const AUTH_URI_KEY = 'auth';
    const AUTH_LOGIN_URI_KEY = 'login';
    const AUTH_LOGOUT_URI_KEY = 'logout';
    const AUTH_CHANGE_PASSWORD_URI_KEY = 'changePassword';

    const SUCCESS_RESPONSE = 'success';
    const FAILURE_RESPONSE = 'error';

    const INPUT_PARAM_LANG = '__lang';
    const INPUT_PARAM_CATE = '__cate';
    const INPUT_PARAM_PID = '__pid';
    const INPUT_PARAM_USER = '__user';
    const INPUT_PARAM_MODULE = '__module';
    const INPUT_PARAM_ACTION = '__action';
    const INPUT_PARAM_SUBACTION = '__subaction';
    
    const PAGINATOR_LIMIT = 10;
    const CSRF_TOKEN_NAME = 'csrf_token';
    
    const EMPTY_CODEBASE = 'EMPTY_CODEBASE';
    const ERROR_RESOURCE_NOT_FOUND = 'NO_ITEM_FOUND';
}
