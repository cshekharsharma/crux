<?php

class StatsController extends AbstractController {

    const MODULE_KEY = 'stats';
    const TOTAL = 'Total';
    
    private $grandTotal = 0;
    private $translationMap = array();

    public function run(Resource $resource) {
        $filteredStats = $this->getFilteredStats();
        $this->displayStats($filteredStats);
    }

    private function getFilteredStats() {
        $matrix = Session::get(Session::SESS_EMPTY_STATS_MATRIX);
        $this->translationMap = Session::get(Session::SESS_ID_NAME_TRANSLATION_MAP);
        if (empty($matrix)) {
            $matrix = $this->getEmptyStatsMatrix();
            Session::set(Session::SESS_EMPTY_STATS_MATRIX, $matrix);
            Session::set(Session::SESS_ID_NAME_TRANSLATION_MAP, $this->translationMap);
        }

        $rawStats = $this->getAllStats();
        if ($rawStats) {
            foreach ($rawStats as $key => $row) {
                $lang = $row[ProgramDetails_DBTable::FK_LANGUAGE_ID];
                $cate = $row[ProgramDetails_DBTable::FK_CATEGORY_ID];
                $matrix[$cate][$lang] = $row['count'];
                $matrix[$cate][self::TOTAL] += $row['count'];
                $matrix[self::TOTAL][$lang] += $row['count'];
                $this->grandTotal += $row['count'];
            }
        }
        return $matrix;
    }

    private function getAllStats() {
        $query = 'SELECT '.ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID.', COUNT(*) AS count FROM ';
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME.' WHERE ';
        $query .= ProgramDetails_DBTable::IS_DELETED.' = "0" GROUP BY ';
        $query .= ProgramDetails_DBTable::FK_LANGUAGE_ID.',';
        $query .= ProgramDetails_DBTable::FK_CATEGORY_ID;
        return DBManager::executeQuery($query, array(), true);
    }

    private function getEmptyStatsMatrix() {
        $queries = array();
        $output = array();
        $matrix = array();
        $temp = array();
        $queryParam = array(
            Language_DBTable::DB_TABLE_NAME => Language_DBTable::IS_DELETED,
            Category_DBTable::DB_TABLE_NAME => Category_DBTable::IS_DELETED
        );
        foreach ($queryParam as $tableName => $columnName) {
            $query = "SELECT * FROM $tableName WHERE $columnName = '0'";
            $output[$tableName] = DBManager::executeQuery($query, array(), true);
        }

        $matrix = array();
        foreach ($output[Language_DBTable::DB_TABLE_NAME] as $key => $languageRow) {
            $tableName = Language_DBTable::DB_TABLE_NAME;
            $idColumn = Language_DBTable::LANGUAGE_ID;
            $nameColumn = Language_DBTable::LANGUAGE_NAME;
            $temp[$languageRow[$idColumn]] = 0;
            $this->translationMap[$tableName][$languageRow[$idColumn]] = $languageRow[$nameColumn];
        }
        $temp[self::TOTAL] = 0;
        foreach ($output[Category_DBTable::DB_TABLE_NAME] as $key => $categoryRow) {
            $tableName = Category_DBTable::DB_TABLE_NAME;
            $idColumn = Category_DBTable::CATEGORY_ID;
            $nameColumn = Category_DBTable::CATEGORY_NAME;
            $matrix[$categoryRow[$idColumn]] = $temp;
            $this->translationMap[$tableName][$categoryRow[$idColumn]] = $categoryRow[$nameColumn];
        }
        $matrix[self::TOTAL] = $temp;
        return $matrix;
    }

    private function displayStats($filteredStats) {
        $this->smarty->assign('STATS', $filteredStats);
        $this->smarty->assign('TOTAL_STR', self::TOTAL);
        $this->smarty->assign('GRAND_TOTAL', $this->grandTotal);
        $this->smarty->assign('LANG_MAP', $this->translationMap[Language_DBTable::DB_TABLE_NAME]);
        $this->smarty->assign('CATE_MAP', $this->translationMap[Category_DBTable::DB_TABLE_NAME]);
        $this->smarty->display('string:'.Display::render(self::MODULE_KEY));
    }
}