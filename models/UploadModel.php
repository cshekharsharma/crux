<?php

class UploadModel extends AbstractModel {
    
    public function insertProgramDescription($params) {
        $query = "INSERT INTO ".ProgramDetails_DBTable::DB_TABLE_NAME." VALUES('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $bindParams = array(
            $params['program_title'], $params['language_id'], $params['category_id'], $params['actual_file_name'],
            $params['stored_file_name'], $params['level'], $params['program_description'], $params['is_verified'],
            Utils::getCurrentDatetime(), Utils::getCurrentDatetime(), $params['created_by'], 0);
        return (DBManager::executeQuery($query, $bindParams, false));
    }
}