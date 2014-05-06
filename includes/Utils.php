<?php
/**
 * Class containing utility methods
 *
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package includes
 */
class Utils {

    public static function getSmarty() {
        if (!Display::$smarty instanceof Smarty) {
            Display::$smarty = new Smarty();
        }

        return Display::$smarty;
    }

    public static function getStoredFileName($actualFileName) {
        $nameparts = explode(".", $actualFileName);
        $extension = end($nameparts);
        return md5(time() . rand(0, 1)) . '.' . $extension;
    }

    public static function getCurrentDatetime() {
        return date(Constants::DB_DATETIME_FORMAT, time());
    }

    public static function createLinks($string) {
        $urlPattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if(preg_match($urlPattern, $string, $url)) {
            $string = preg_replace($urlPattern, "<a target=\"_blank\" href=".$url[0].">".$url[0]."</a> ", $string);
        }
        return $string;
    }

    public static function createNewFile($filePath, $contents, $permission = '755') {
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

    public static function displayVariableValues($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        die;
    }
}