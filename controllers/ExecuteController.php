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
    const ERROR_FILE = '/tmp/errors';
    const SH_SCRIPT  = '/tmp/code_exec_script.sh';

    public function __construct() {
        parent::__construct();
        $this->view = new ExecuteView();
    }
    
    /**
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
        $fileDir = Configuration::get(Configuration::CODE_BASE_DIR)
        . $language . '/' . $category;
        $fileName = $details[ProgramDetails_DBTable::STORED_FILE_NAME];
        switch ($language) {
            case 'c':
                $this->cLang($fileDir, $fileName);
                break;
            default:
                echo 'This feature currently support only C Language programs!';
        }
    }

    protected function cLang($fileDir, $fileName) {
        chdir($fileDir);
        $cmd = '`gcc ' . $fileName . ' &> '.self::ERROR_FILE.'`';
        file_put_contents(self::SH_SCRIPT, $cmd);
        shell_exec('bash ' . self::SH_SCRIPT);
        $err = file_get_contents(self::ERROR_FILE);
        if (empty($err)) {
            if (is_file('a.out')) {
                $output = shell_exec('./a.out');
                $this->setBean(array('message' => $output));
            } else {
                $this->setBean(
                    array(
                        'message' => '<h2>Executable could not be located! Exiting!</h2>'
                    )
                );
            }
        } else {
            $this->setBean(array('message' => $err));
        }
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }
}