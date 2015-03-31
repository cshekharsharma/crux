<?php
/**
 * Core Application Class, Super class of all MVC classes.
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @version 1.0.0
 * @since Jun 16, 2014
 */
abstract class Application {

    /**
     * Bean data
     *
     * @var mixed
     */
    private static $bean;

    public function getBean() {
        return self::$bean;
    }

    public function setBean($bean) {
        self::$bean = $bean;
    }

    public function resetBean() {
        self::$bean = null;
        return $this;
    }
}
