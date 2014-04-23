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

    public static function displayVariableValues($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        die;
    }
}