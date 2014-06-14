<?php

class DownloadModel extends AbstractModel {

    public function getFileInfoFromDB($pid) {
        $fileInfo = array();
        $query = "SELECT * FROM ";
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME." WHERE ";
        $query .= ProgramDetails_DBTable::PROGRAM_ID."=? AND ";
        $query .= ProgramDetails_DBTable::IS_DELETED."='0'";
        $resultSet = DBManager::executeQuery($query, array($pid), true);
        if (!empty($resultSet)) {
            $fileInfo = current($resultSet);
        }
        return $fileInfo;
    }
}