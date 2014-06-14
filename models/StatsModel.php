<?php

class StatsModel extends AbstractModel {
    
    public function getAllStats() {
        $query = 'SELECT '.ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID.', COUNT(*) AS count FROM ';
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= ProgramDetails_DBTable::IS_DELETED.' = "0" GROUP BY ';
        $query .= ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID;
        return DBManager::executeQuery($query, array(), true);
    }
    
    public function getColumnValue($table, $column) {
        $query = "SELECT * FROM $table WHERE $column = '0'";
        return DBManager::executeQuery($query, array(), true);
    }
}