<?php
/**
 * Controller class for file download module
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since Jun 20, 2014
 */
class DownloadController extends AbstractController {

    const MODULE_KEY = 'download';
    
    public function __construct() {
        parent::__construct();
        $this->model = new DownloadModel();
    }
    
    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $inputParams = $resource->getParams();
        $pid = $inputParams[Constants::INPUT_PARAM_ACTION];
        if (!empty($pid)) {
            $this->downloadFile($pid);
        } else {
            throw new Exception("No File Info Provided!");
        }
    }

    /**
     * Downloads file on client machine
     * 
     * @param int $pid
     */
    private function downloadFile($pid) {
        $fileInfo = $this->getModel()->getFileInfoFromDB($pid);
        $fileContents = $this->getFileContents($fileInfo);
        $fileName = $fileInfo[ProgramDetails_DBTable::ACTUAL_FILE_NAME];
        $this->sendDownloadHeaders($fileName, $fileContents);
        die($fileContents);
    }

    /**
     * Get source code (content) of requested file
     * 
     * @param array fileInfo
     * @return string
     */
    private function getFileContents(array $fileInfo) {
        $filePath = Configuration::get(Configuration::CODE_BASE_DIR);
        $filePath .= $fileInfo[ProgramDetails_DBTable::FK_LANGUAGE_ID].'/';
        $filePath .= $fileInfo[ProgramDetails_DBTable::FK_CATEGORY_ID].'/';
        $filePath .= $fileInfo[ProgramDetails_DBTable::STORED_FILE_NAME];
        $fileContents = @file_get_contents($filePath);
        return $fileContents;
    }

    /**
     * Send HTTP headers with appropriate information for file download
     * 
     * @param string $fileName
     * @param string $fileContents
     */
    private function sendDownloadHeaders($fileName, $fileContents) {
        header("Pragma: public", true);
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".$fileName);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".strlen($fileContents));
    }
}