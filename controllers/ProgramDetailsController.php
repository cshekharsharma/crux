<?php
/**
 * Controller class for ProgramDetails module
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class ProgramDetailsController extends AbstractController {

    const MODULE_KEY = 'programDetails';

    public function run(Resource $resource) {
    }

    /**
     * Get program info for given PID
     * 
     * @param int  $pid
     * @return array
     */
    public function getProgramListById($pid) {
        $query = "SELECT * FROM ".ProgramDetails_DBTable::DB_TABLE_NAME." WHERE ";
        $query .= ProgramDetails_DBTable::PROGRAM_ID."='".$pid."' AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= 0";
        $resultSet = DBManager::executeQuery($query, array(), true);
        return current($resultSet);
    }
    
    /**
     * Get all active record count from program details table
     * 
     * @return int
     */
    public function getAllRecordCount() {
        $query = 'SELECT COUNT(*) AS count FROM '.ProgramDetails_DBTable::DB_TABLE_NAME
            . ' WHERE '.ProgramDetails_DBTable::IS_DELETED.'=0';
        $result = DBManager::executeQuery($query, array(), true);
        return $result[0]['count'];
    }

    /**
     * Delete given program from system
     * 
     * @param int $pid
     * @return boolean
     */
    public function deleteProgram($pid) {
        $programInfo = $this->getProgramListById($pid);
        $query = "UPDATE ".ProgramDetails_DBTable::DB_TABLE_NAME." SET ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= '1' WHERE ";
        $query .= ProgramDetails_DBTable::PROGRAM_ID."='".$pid."' AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= 0";
        if (DBManager::executeQuery($query)) {
            $fileToUnlink = Configuration::get(Configuration::CODE_BASE_DIR) .
            $programInfo[ProgramDetails_DBTable::FK_LANGUAGE_ID]."/".
            $programInfo[ProgramDetails_DBTable::FK_CATEGORY_ID]."/".
            $programInfo[ProgramDetails_DBTable::STORED_FILE_NAME];
            unlink($fileToUnlink);
            return true;
        } else {
            Logger::getLogger()->LogFatal("Unable to delete file => ".$fileToUnlink);
            return false;
        }
    }
}