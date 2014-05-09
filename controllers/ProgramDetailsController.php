<?php

class ProgramDetailsController extends BaseController {

    const MODULE_KEY = "programDetails";

    public function run(Resource $resource) {

    }

    public function getProgramListById($pid) {
        $query = "SELECT * FROM ".ProgramDetails_DBTable::DB_TABLE_NAME." WHERE ";
        $query .= ProgramDetails_DBTable::PROGRAM_ID."='".$pid."' AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= 0";
        $resultSet = DBManager::executeQuery($query, array(), true);
        return current($resultSet);
    }

    public function deleteProgram($pid) {
        $programInfo = $this->getProgramListById($pid);
        $query = "UPDATE ".ProgramDetails_DBTable::DB_TABLE_NAME." SET ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= '1' WHERE ";
        $query .= ProgramDetails_DBTable::PROGRAM_ID."='".$pid."' AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED."= 0";
        if (DBManager::executeQuery($query)) {
            $fileToUnlink = Configuration::get('CODE_BASE_DIR') .
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