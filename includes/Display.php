<?php
/**
 * Class for managing views, template rendering and display
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @since May 11, 2014
 */
class Display {

    /**
     * Smarty instance
     *
     * @var Smarty
     */
    public static $smarty;
    
    /**
     * Get smarty object for templating. Uses Singleton pattern
     *
     * @return Smarty
     */
    public static function getSmarty() {
        if (!self::$smarty instanceof Smarty) {
            self::$smarty = new Smarty();
        }
    
        return self::$smarty;
    }
    

}