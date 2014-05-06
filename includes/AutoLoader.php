<?php


/**
 * Custom Runtime AutoLoader class for APP
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 */
class AutoLoader {

    private $classPathMap;
    private $libraryClassPaths;

    const UI_DIR = "ui/";
    const LIBRARY_DIR = "library/";
    const INCLUDE_DIR = "includes/";
    const DB_TABLE_DIR = "dbtables/";
    const CONTROLLER_DIR = "controllers/";

    public function __construct() {
        $this->classPathMap = array(
            // Include classes
            'Error' => self::INCLUDE_DIR.'Error.php',
            'Utils' => self::INCLUDE_DIR.'Utils.php',
            'Logger' => self::INCLUDE_DIR.'Logger.php',
            'Session' => self::INCLUDE_DIR.'Session.php',
            'Display' => self::INCLUDE_DIR.'Display.php',
            'Resource' => self::INCLUDE_DIR.'Resource.php',
            'Constants' => self::INCLUDE_DIR.'Constants.php',
            'DBManager' => self::INCLUDE_DIR.'DBManager.php',
            'Configuration' => self::INCLUDE_DIR.'Configuration.php',
            'RequestManager' => self::INCLUDE_DIR.'RequestManager.php',
            'ServiceResponse' => self::INCLUDE_DIR.'ServiceResponse.php',
            'ResourceProvider' => self::INCLUDE_DIR.'ResourceProvider.php',

            // Controller Classes
            'BaseController' => self::CONTROLLER_DIR.'BaseController.php',
            'AuthController' => self::CONTROLLER_DIR.'AuthController.php',
            'UsersController' => self::CONTROLLER_DIR.'UsersController.php',
            'IndexController' => self::CONTROLLER_DIR.'IndexController.php',
            'SearchController' => self::CONTROLLER_DIR.'SearchController.php',
            'UploadController' => self::CONTROLLER_DIR.'UploadController.php',
            'EditorController' => self::CONTROLLER_DIR.'EditorController.php',
            'CategoryController' => self::CONTROLLER_DIR.'CategoryController.php',
            'LanguageController' => self::CONTROLLER_DIR.'LanguageController.php',
            'DownloadController' => self::CONTROLLER_DIR.'DownloadController.php',
            'ExplorerController' => self::CONTROLLER_DIR.'ExplorerController.php',

            // DB tables
            'AbstractDbTable' => self::DB_TABLE_DIR.'AbstractDbTable.php',
            'Users_DBTable' => self::DB_TABLE_DIR.'Users_DBTable.php',
            'Language_DBTable' => self::DB_TABLE_DIR.'Language_DBTable.php',
            'Category_DBTable' => self::DB_TABLE_DIR.'Category_DBTable.php',
            'ProgramDetails_DBTable' => self::DB_TABLE_DIR.'ProgramDetails_DBTable.php',
        );

        $this->libraryClassPaths = array(
            'smartyBase' => self::LIBRARY_DIR.'smarty/libs/',
            'smartySysplugins' => self::LIBRARY_DIR.'smarty/libs/sysplugins/',
        );
    }

    private function getLibraryClassPath($className) {
        $className = $this->getSmartyClassPath($className);
        if (!empty($className)) return $className;

        return false;
    }

    private function getSmartyClassPath($className) {
        $classFile = $this->libraryClassPaths['smartyBase'].$className.'.class.php';
        if (file_exists($classFile)) return $classFile;

        $classFile = $this->libraryClassPaths['smartySysplugins'].strtolower($className).'.php';
        if (file_exists($classFile)) return $classFile;

        return false;
    }

    public function loadClass($className) {
        $classPath = $this->getClassPath($className);
        try {
            if (!empty($classPath)) {
                require_once $classPath;
            } else {
                $classPath = $this->getLibraryClassPath($className);
                if (!empty($classPath)) {
                    require_once $classPath;
                }
            }
        } catch (Exception $e) {
            Logger::getLogger()->LogFatal("Class '$className' not found");
        }
    }

    private function getClassPath($className) {
        if (!empty($this->classPathMap[$className])) {
            return $this->classPathMap[$className];
        } else {
            return false;
        }
    }
}

$autoloader = new AutoLoader();

spl_autoload_register(array($autoloader, 'loadClass'));
