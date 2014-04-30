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
    
    public static function displayVariableValues($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        die;
    }
}