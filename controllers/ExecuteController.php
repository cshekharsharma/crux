<?php

/**
 * Controller for automated source code execution
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Sept 07, 2014
 */
class ExecuteController extends AbstractController {

    const MODULE_KEY = 'execute';

    const METADATA_FILE = '/tmp/metadata';

    const SH_SCRIPT = '/tmp/code_exec_script.sh';

    protected $executionResponse;

    public function __construct() {
        parent::__construct();
        $this->view = new ExecuteView();
        $this->executionResult = array(
            'code' => '',
            'msg' => ''
        );
    }

    protected function getExecResponse($code, $msg) {
        $this->executionResponse['code'] = $code;
        $this->executionResponse['msg'] = $msg;
        return $this->executionResponse;
    }

    /**
     *
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $pid = RequestManager::getParam('id');
        $this->execute($pid);
    }

    protected function execute($pid) {
        $details = (new ProgramDetailsController())->getProgramListById($pid);
        $language = $details[ProgramDetails_DBTable::FK_LANGUAGE_ID];
        $category = $details[ProgramDetails_DBTable::FK_CATEGORY_ID];
        $fileDir = Configuration::get(Configuration::CODE_BASE_DIR) . $language . '/' . $category;
        $actualName = $details[ProgramDetails_DBTable::ACTUAL_FILE_NAME];
        $fileName = $details[ProgramDetails_DBTable::STORED_FILE_NAME];
        switch ($language) {
            case Constants::PROG_LANG_C :
                $this->executeCLang($fileDir, $fileName, $actualName);
                break;
            case Constants::PROG_LANG_PHP :
                $this->executePhp($fileDir, $fileName, $actualName);
                break;
            case Constants::PROG_LANG_JAVA :
                $this->executeJava($fileDir, $fileName, $actualName);
                break;
            default :
                echo 'This feature only works for C, Java and PHP programs!';
        }
    }

    protected function executeCLang($fileDir, $fileName, $actualName) {
        $terminalOutput = [
            'CMD' => './a.out',
            'OUTPUT' => ''
        ];
        $temporaryFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $actualName;
        copy($fileDir . DIRECTORY_SEPARATOR . $fileName, $temporaryFilePath);
        $cmd = '`gcc ' . $temporaryFilePath . ' &> ' . self::METADATA_FILE . '`';
        file_put_contents(self::SH_SCRIPT, $cmd);
        $prevDir = getcwd();
        chdir(sys_get_temp_dir());
        shell_exec('bash ' . self::SH_SCRIPT);
        $errors = @file_get_contents(self::METADATA_FILE);
        if (empty($errors)) {
            if (is_file('a.out')) {
                $output = shell_exec('./a.out');
                $terminalOutput['OUTPUT'] = $output;
                $this->setBean($this->getExecResponse(Constants::SUCCESS_RESPONSE, $terminalOutput));
            } else {
                $terminalOutput['OUTPUT'] = 'a.out: No such file or directory';
                $this->setBean($this->getExecResponse(Constants::SUCCESS_RESPONSE, $terminalOutput));
            }
        } else {
            $terminalOutput['CMD'] = "gcc $actualName";
            $terminalOutput['OUTPUT'] = $errors;
            $this->setBean($this->getExecResponse(Constants::FAILURE_RESPONSE, $terminalOutput));
        }
        chdir($prevDir);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    protected function executePhp($fileDir, $fileName, $actualName) {
        $terminalOutput = [
            'CMD' => "php -f $actualName",
            'OUTPUT' => ''
        ];
        $temporaryFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $actualName;
        copy($fileDir . DIRECTORY_SEPARATOR . $fileName, $temporaryFilePath);
        $cmd = '`php ' . $temporaryFilePath . ' > ' . self::METADATA_FILE . '`';
        $output = shell_exec($cmd);
        if (empty($output)) {
            $terminalOutput['OUTPUT'] = @file_get_contents(self::METADATA_FILE);
        } else {
            $terminalOutput['OUTPUT'] = $output;
        }
        $this->setBean($this->getExecResponse(Constants::SUCCESS_RESPONSE, $terminalOutput));
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    protected function executeJava($fileDir, $fileName, $actualName) {
        $terminalOutput = [
            'CMD' => 'java',
            'OUTPUT' => ''
        ];
        $temporaryFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $actualName;
        copy($fileDir . DIRECTORY_SEPARATOR . $fileName, $temporaryFilePath);
        $cmd = '`javac ' . $actualName . ' &> ' . self::METADATA_FILE . '`';
        file_put_contents(self::SH_SCRIPT, $cmd);
        $prevDir = getcwd();
        chdir(sys_get_temp_dir());
        shell_exec('bash ' . self::SH_SCRIPT);
        $errors = @file_get_contents(self::METADATA_FILE);

        if (empty($errors)) {
            $nameparts = explode(".", $actualName);
            $classExt = end($nameparts);
            unset($nameparts[count($nameparts) - 1]);
            $className = implode(".", $nameparts);
            $classNameFile = $className . '.class';
            $terminalOutput['CMD'] = "java $className";
            if (is_file($classNameFile)) {
                $output = shell_exec("java $className");
                $terminalOutput['OUTPUT'] = $output;
                $this->setBean($this->getExecResponse(Constants::SUCCESS_RESPONSE, $terminalOutput));
            } else {
                $terminalOutput['OUTPUT'] = "$className: No such class file found";
                $this->setBean($this->getExecResponse(Constants::SUCCESS_RESPONSE, $terminalOutput));
            }
        } else {
            $terminalOutput['CMD'] = "javac $actualName";
            $terminalOutput['OUTPUT'] = $errors;
            $this->setBean($this->getExecResponse(Constants::FAILURE_RESPONSE, $terminalOutput));
        }
        chdir($prevDir);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }
}
