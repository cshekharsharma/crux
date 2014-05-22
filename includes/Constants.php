<?php


/**
 * Application Constant Class
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class Constants {

    const APP_NAME = 'code.me';
    const APP_VERSION = '1.0.0';

    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';

    const DATA_DIR = 'data/';
    const VIEW_DIR = 'views/';
    const CACHE_DIR = 'cache/';
    const LIBRARY_DIR = 'library/';
    const INCLUDE_DIR = 'includes/';
    const DB_TABLE_DIR = 'dbtables/';
    const CONTROLLER_DIR = 'controllers/';

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

    const PASSWORD_CHANGED_MSG = 'Password successfully changed';

}