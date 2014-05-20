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

    public function __construct() {
        require_once 'includes/Constants.php';
        $this->classPathMap = array(
            // Include classes
            'Error' => Constants::INCLUDE_DIR.'Error.php',
            'Utils' => Constants::INCLUDE_DIR.'Utils.php',
            'Logger' => Constants::INCLUDE_DIR.'Logger.php',
            'Session' => Constants::INCLUDE_DIR.'Session.php',
            'Display' => Constants::INCLUDE_DIR.'Display.php',
            'Resource' => Constants::INCLUDE_DIR.'Resource.php',
            'Constants' => Constants::INCLUDE_DIR.'Constants.php',
            'DBManager' => Constants::INCLUDE_DIR.'DBManager.php',
            'Configuration' => Constants::INCLUDE_DIR.'Configuration.php',
            'RequestManager' => Constants::INCLUDE_DIR.'RequestManager.php',
            'Response' => Constants::INCLUDE_DIR.'Response.php',
            'ResourceProvider' => Constants::INCLUDE_DIR.'ResourceProvider.php',

            // Controller Classes
            'BaseController' => Constants::CONTROLLER_DIR.'BaseController.php',
            'AuthController' => Constants::CONTROLLER_DIR.'AuthController.php',
            'UsersController' => Constants::CONTROLLER_DIR.'UsersController.php',
            'StatsController' => Constants::CONTROLLER_DIR.'StatsController.php',
            'IndexController' => Constants::CONTROLLER_DIR.'IndexController.php',
            'SearchController' => Constants::CONTROLLER_DIR.'SearchController.php',
            'UploadController' => Constants::CONTROLLER_DIR.'UploadController.php',
            'EditorController' => Constants::CONTROLLER_DIR.'EditorController.php',
            'ContentController' => Constants::CONTROLLER_DIR.'ContentController.php',
            'CategoryController' => Constants::CONTROLLER_DIR.'CategoryController.php',
            'LanguageController' => Constants::CONTROLLER_DIR.'LanguageController.php',
            'DownloadController' => Constants::CONTROLLER_DIR.'DownloadController.php',
            'ExplorerController' => Constants::CONTROLLER_DIR.'ExplorerController.php',
            'ProgramDetailsController' => Constants::CONTROLLER_DIR.'ProgramDetailsController.php',

            // DB tables
            'AbstractDbTable' => Constants::DB_TABLE_DIR.'AbstractDbTable.php',
            'Users_DBTable' => Constants::DB_TABLE_DIR.'Users_DBTable.php',
            'Language_DBTable' => Constants::DB_TABLE_DIR.'Language_DBTable.php',
            'Category_DBTable' => Constants::DB_TABLE_DIR.'Category_DBTable.php',
            'ProgramDetails_DBTable' => Constants::DB_TABLE_DIR.'ProgramDetails_DBTable.php',
        );

        $this->libraryClassPaths = array(
            'smartyBase' => Constants::LIBRARY_DIR.'smarty/libs/',
            'smartySysplugins' => Constants::LIBRARY_DIR.'smarty/libs/sysplugins/',
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

    /**
     * Find class path for given class and loads it by including class file.
     * 
     * @param string $className
     */
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

// Registering autoloader function for app
spl_autoload_register(array($autoloader, 'loadClass'));
