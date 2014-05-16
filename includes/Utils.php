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
     * Prints variable formatted structure and value of variable
     * 
     * @param mixed $var
     */
    public static function displayVariableValues($var, $exit = false) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        die;
    }
}