<?php
/**
 * Model class for stats data processing
 * 
 * @author Chandra Shekhar <shekahrsharma705@gmail.com>
 * @since Sep 12, 2014
 */
class StatsModel extends AbstractModel {

    /**
     * Get overall stats summery
     * 
     * @return array|null
     */
    public function getAllStats() {
        $query = 'SELECT '.ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID.', COUNT(*) AS count FROM ';
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= ProgramDetails_DBTable::IS_DELETED.' = "0" GROUP BY ';
        $query .= ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID;
        return DBManager::executeQuery($query, array(), true);
    }

    /**
     * generic function for condition checks in given table
     * 
     * @return array|null
     */
    public function getColumnValue($table, $column) {
        $query = "SELECT * FROM $table WHERE $column = '0'";
        return DBManager::executeQuery($query, array(), true);
    }

    /**
     * Get ratio of verified and non-verified codes in db
     * 
     * @return array
     */
    public function getCodeVerificationStats() {
        $query = 'SELECT COUNT(*) AS total,
            SUM(CASE WHEN '. ProgramDetails_DBTable::IS_VERIFIED . '="1"
                THEN 1 ELSE 0 END) AS verified_count FROM ' . ProgramDetails_DBTable::DB_TABLE_NAME
                . ' WHERE ' . ProgramDetails_DBTable::IS_DELETED . '=0';
        return current(DBManager::executeQuery($query, array(), true));
    }

    /**
     * Get aggregated program count on daily basis
     * 
     * @return array
     */
    public function getDayWiseProgramCount() {
        $createdDate = ProgramDetails_DBTable::CREATED_ON;
        $isDeleted = ProgramDetails_DBTable::IS_DELETED;
        $tableName = ProgramDetails_DBTable::DB_TABLE_NAME;
        $sql = "SELECT COUNT(*) AS count, {$createdDate} AS date FROM {$tableName}"
        . " WHERE {$isDeleted} = 0 GROUP BY DATE({$createdDate}) ORDER BY DATE({$createdDate})";
        return DBManager::executeQuery($sql, array(), true);
    }

    /**
     * Get weekly code frequency
     * 
     * @return array
     */
    public function getCodeFrequency() {
        $weeks = array();
        $programcount = $this->getDayWiseProgramCount();
        $currentWeek = date('W');
        for ($i = $currentWeek-10; $i < $currentWeek; $i++) {
            $key = 'W'.$i.'-'.date('Y');
            $weeks[$key] = 0;
        }
        
        foreach ($programcount as $key => $data) {
            $week = date('W', strtotime($data['date']));
            $year = date('Y', strtotime($data['date']));
            $weeks['W'.$week.'-'.$year]++;
        }
        return $weeks;
    }
    
    public function getCategoryPieStats() {
        $sql = "SELECT COUNT(*) as count, ".ProgramDetails_DBTable::FK_CATEGORY_ID
        . " AS category FROM ".ProgramDetails_DBTable::DB_TABLE_NAME
        . ' WHERE '.ProgramDetails_DBTable::IS_DELETED.'=0 GROUP BY '
            . ProgramDetails_DBTable::FK_CATEGORY_ID; 
        return DBManager::executeQuery($sql, array(), true);
    }
}