<?php
/**
 * Class containing utility methods
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class Utils {

    /**
     * Get smarty object for templating. Uses Singleton pattern
     *
     * @return Smarty
     */
    public static function getSmarty() {
        if (!Display::$smarty instanceof Smarty) {
            Display::$smarty = new Smarty();
        }

        return Display::$smarty;
    }

    /**
     * Get hashed file name which is stored on disk
     *
     * @param string $actualFileName
     * @return string
     */
    public static function getStoredFileName($actualFileName) {
        $nameparts = explode(".", $actualFileName);
        $extension = end($nameparts);
        return md5(time() . rand(0, 1)) . '.' . $extension;
    }

    /**
     * Get current datetime in standard format
     *
     * @return string
     */
    public static function getCurrentDatetime() {
        return date(Constants::DB_DATETIME_FORMAT, time());
    }

    /**
     * Parse given string and checks if it contains any valid URL, altered string
     * is returned with links for those URLs
     *
     * @param string $string
     * @return mixed
     */
    public static function createLinks($string) {
        $urlPattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if(preg_match($urlPattern, $string, $url)) {
            $string = preg_replace($urlPattern, "<a target=\"_blank\" href=".$url[0].">".$url[0]."</a> ", $string);
        }
        return $string;
    }

    /**
     * Utility function for creating new file and write provided contents
     *
     * @param string $filePath
     * @param string $contents
     * @param integer $permission
     * @return boolean
     */
    public static function createNewFile($filePath, $contents, $permission = 777) {
        $fp = fopen($filePath, 'w+');
        if ($fp) {
            fwrite($fp, $contents);
            fclose($fp);
            return true;
        } else {
            Logger::getLogger()->LogFatal("Could not open file ".$filePath);
            return false;
        }
    }

    /**
     * Returns appropriate editor mode for code syntax highlighting according to
     * language name. Returns default editor for C/C++ if no details provided
     *
     * @param array|false $programDetails
     * @return string $editorMode
     */
    public static function getCodeEditorMode($programDetails = false) {
        $editorMode = 'c_cpp';
        if (!empty($programDetails)) {
            $cppArray = array('c', 'cpp');
            if (!in_array($programDetails[ProgramDetails_DBTable::FK_LANGUAGE_ID], $cppArray)) {
                $editorMode = $programDetails[ProgramDetails_DBTable::FK_LANGUAGE_ID];
            }
        }
        return $editorMode;
    }

    /**
     * Returns appropriate editor theme for logged in user.
     * Try to get theme from user preferences, if not found then returns default.
     *
     * @return string $themeInfo
     */
    public static function getCodeEditorTheme() {
        $themeInfo = array();
        $userPref = Session::get(Session::SESS_USER_PREF_KEY);
        if (!empty($userPref[PreferenceKeys::CODE_EDITOR_THEME])) {
            $themeInfo['id'] = $userPref[PreferenceKeys::CODE_EDITOR_THEME];
        } else {
            $themeInfo['id'] = Configuration::get(Configuration::CODE_EDITOR_THEME);
        }
        $themeInfo['name'] = self::getAceEditorThemes($themeInfo['id']);
        return $themeInfo;
    }

    /**
     * Tells if request is an AJAX request, by checking appropriate header
     *
     * @return boolean
     */
    public static function isAjaxRequest() {
        $hasHeader = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $isAjaxHeader = ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
        return $hasHeader && $isAjaxHeader;
    }

    /**
     * Returns list of all Ace editor theme names
     *
     * @return array
     */
    public static function getAceEditorThemes($themeId = false) {
        $themes = array(
            'ambiance' => 'Ambiance',
            'chaos' => 'Chaos',
            'chrome' => 'Chrome',
            'clouds' => 'Clouds',
            'clouds_midnight' => 'Clouds Midnight',
            'cobalt' => 'Cobalt',
            'crimson_editor' => 'Crimson',
            'dawn' => 'Dawn',
            'dreamweaver' => 'DreamWeaver',
            'eclipse' => 'Eclipse',
            'github' => 'GitHub',
            'idle_fingers' => 'Idle Fingers',
            'katzenmilch' => 'Katzenmilch',
            'kr' => 'KR',
            'kuroir' => 'Kuroir',
            'merbivore' => 'Merbivore',
            'merbivore_soft' => 'Merbivore Soft',
            'mono_industrial' => 'Mono Industrial',
            'monokai' => 'Monokai',
            'pastel_on_dark' => 'Pastel On Dark',
            'solarized_dark' => 'Solarized Dark',
            'solarized_light' => 'Solarized Light',
            'terminal' => 'Terminal',
            'textmate' => 'TextMate',
            'tomorrow' => 'Tomorrow',
            'tomorrow_night' => 'Tomorrow Night',
            'tomorrow_night_blue' => 'Tomorrow Night Blue',
            'tomorrow_night_bright' => 'Tomorrow Night Bright',
            'tomorrow_night_eighties' => 'Tomorrow Night Eighties',
            'twilight' => 'Twilight',
            'vibrant_ink' => 'Vibrant Ink',
            'xcode' => 'XCode'
        );

        return (empty($themeId)) ? $themes : $themes[$themeId];
    }

    /**
     * Returns currently logged in user's id
     *
     * @return string
     */
    public static function getLoggedInUserId() {
        $loggedInUserDetails = Session::get(Session::SESS_USER_DETAILS);
        $loggedInUserId = $loggedInUserDetails[Users_DBTable::USER_ID];
        return $loggedInUserId;
    }

    public static function isRunningFromCLI() {
        return (php_sapi_name() === 'cli');
    }

    /**
     * Prints formatted msg on CLI
     *
     * @param string $msg
     */
    public static function printCLIMessages($msg) {
        if (self::isRunningFromCLI()) {
            $border = str_repeat("#", strlen($msg) + 3);
            echo $border.PHP_EOL.'#'.PHP_EOL;
            echo "#  ".$msg.PHP_EOL.'#'.PHP_EOL;
            echo $border.PHP_EOL;
        } else {
            Logger::getLogger()->LogWarn('Trying to print CLI_Message while file not executing from CLI');
        }
    }

    /**
     * Get datatype of given variable
     *
     * @param mixed $object
     * @return string
     */
    public function getObjectType($object) {
        if (strtolower(gettype($object)) == 'object') {
            return get_class($object);
        } else {
            return ucfirst(gettype($object));
        }
    }

    /**
     * Prints variable formatted structure and value of variable
     *
     * @param mixed $var
     */
    public static function debugVariable($var, $exit = false, $displayTrace = true) {
        $debugTrace = end(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT));
        $debugVar = print_r($var, true);
        $smarty = self::getSmarty();
        $smarty->assign('DEBUG_VAR', $debugVar);
        if ($displayTrace) {
            $smarty->assign('STACK_TRACE', $debugTrace);
        }
        $smarty->display('string:'. @file_get_contents('templates/Errors/tpls/debugTrace.htpl'));
        if ($exit) {
            exit();
        }
    }
}
