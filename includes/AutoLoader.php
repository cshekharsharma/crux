<?php

/**
 * Custom Runtime AutoLoader class for APP.
 * It contains path-map of all classes for loading when needed
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @since 11 May, 2014
 */
class AutoLoader {

    private $classPathMap;
    private $libraryClassPaths;

    public function __construct() {
        require_once 'includes/Constants.php';
        $this->classPathMap = array(

            // Helper classes
            'Resource' => Constants::HELPER_CLASSES_DIR.'Resource.php',
            'Response' => Constants::HELPER_CLASSES_DIR.'Response.php',
            'PreferenceKeys' => Constants::HELPER_CLASSES_DIR.'PreferenceKeys.php',

            // Include classes
            'Error' => Constants::INCLUDE_DIR.'Error.php',
            'Utils' => Constants::INCLUDE_DIR.'Utils.php',
            'Logger' => Constants::INCLUDE_DIR.'Logger.php',
            'Session' => Constants::INCLUDE_DIR.'Session.php',
            'Display' => Constants::INCLUDE_DIR.'Display.php',
            'Profiler' => Constants::INCLUDE_DIR.'Profiler.php',
            'Messages' => Constants::INCLUDE_DIR.'Messages.php',
            'Constants' => Constants::INCLUDE_DIR.'Constants.php',
            'DBManager' => Constants::INCLUDE_DIR.'DBManager.php',
            'Application' => Constants::INCLUDE_DIR.'Application.php',
            'Configuration' => Constants::INCLUDE_DIR.'Configuration.php',
            'RequestManager' => Constants::INCLUDE_DIR.'RequestManager.php',
            'ResourceProvider' => Constants::INCLUDE_DIR.'ResourceProvider.php',

            // Controller Classes
            'AbstractController' => Constants::CONTROLLER_DIR.'AbstractController.php',
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
            'UserPreferencesController' => Constants::CONTROLLER_DIR.'UserPreferencesController.php',

            // Model Classes
            'AbstractModel' => Constants::MODEL_DIR.'AbstractModel.php',
            'AuthModel' => Constants::MODEL_DIR.'AuthModel.php',
            'IndexModel' => Constants::MODEL_DIR.'IndexModel.php',
            'StatsModel' => Constants::MODEL_DIR.'StatsModel.php',
            'SearchModel' => Constants::MODEL_DIR.'SearchModel.php',
            'EditorModel' => Constants::MODEL_DIR.'EditorModel.php',
            'UploadModel' => Constants::MODEL_DIR.'UploadModel.php',
            'DownloadModel' => Constants::MODEL_DIR.'DownloadModel.php',
            'ExplorerModel' => Constants::MODEL_DIR.'ExplorerModel.php',

            // View Classes
            'AbstractView' => Constants::VIEW_DIR.'AbstractView.php',
            'AuthView' => Constants::VIEW_DIR.'AuthView.php',
            'IndexView' => Constants::VIEW_DIR.'IndexView.php',
            'StatsView' => Constants::VIEW_DIR.'StatsView.php',
            'SearchView' => Constants::VIEW_DIR.'SearchView.php',
            'UploadView' => Constants::VIEW_DIR.'UploadView.php',
            'EditorView' => Constants::VIEW_DIR.'EditorView.php',
            'ContentView' => Constants::VIEW_DIR.'ContentView.php',
            'ExplorerView' => Constants::VIEW_DIR.'ExplorerView.php',

            // DB tables
            'AbstractDBTable' => Constants::DB_TABLE_DIR.'AbstractDBTable.php',
            'Users_DBTable' => Constants::DB_TABLE_DIR.'Users_DBTable.php',
            'Language_DBTable' => Constants::DB_TABLE_DIR.'Language_DBTable.php',
            'Category_DBTable' => Constants::DB_TABLE_DIR.'Category_DBTable.php',
            'ProgramDetails_DBTable' => Constants::DB_TABLE_DIR.'ProgramDetails_DBTable.php',
            'UserPreferences_DBTable' => Constants::DB_TABLE_DIR.'UserPreferences_DBTable.php',
        );

        $this->libraryClassPaths = array(
            'smartyBase' => Constants::LIBRARY_DIR.'smarty/libs/',
            'smartySysplugins' => Constants::LIBRARY_DIR.'smarty/libs/sysplugins/',
        );
    }

    /**
     * Finds all library class paths, which are not registered with autoloader
     *
     * @param string $className
     * @return string|boolean
     */
    private function getLibraryClassPath($className) {
        $className = $this->getSmartyClassPath($className);
        if (!empty($className)) return $className;

        return false;
    }

    /**
     * Get class path for given smarty class
     *
     * @param unknown $className
     * @return string|boolean
     */
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
