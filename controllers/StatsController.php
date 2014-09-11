<?php
/**
 * Controller class for Stats module
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class StatsController extends AbstractController {

    const MODULE_KEY = 'stats';
    const TOTAL = 'Total';

    private $grandTotal = 0;
    private $allStats = array();
    private $translationMap = array();

    /**
     * @var ChartHelper
    */
    protected $chartHelper;

    public function __construct() {
        parent::__construct();
        $this->model = new StatsModel();
        $this->view = new StatsView();
        $this->chartHelper = new ChartHelper();
    }

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $filteredStats = $this->getFilteredStats();
        $this->createBean($filteredStats);
        $this->getView()->setViewName(self::MODULE_KEY)->display();
    }

    /**
     * Get category and language based filtered results
     *
     * @return Ambigous <mixed, NULL, unknown, multitype:number >
     */
    private function getFilteredStats() {
        $matrix = Session::get(Session::SESS_EMPTY_STATS_MATRIX);
        $this->translationMap = Session::get(Session::SESS_ID_NAME_TRANSLATION_MAP);
        if (empty($matrix)) {
            $matrix = $this->getEmptyStatsMatrix();
            Session::set(Session::SESS_EMPTY_STATS_MATRIX, $matrix);
            Session::set(Session::SESS_ID_NAME_TRANSLATION_MAP, $this->translationMap);
        }

        $this->allStats = $rawStats = $this->getModel()->getAllStats();
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

    /**
     * Get empty container map for stats
     *
     * @return array
     */
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
            $output[$tableName] = $this->getModel()->getColumnValue($tableName, $columnName);
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

    protected function getSummeryChartJson($filteredStats) {
        $xAxis = (new CategoryController())->getCategoryList(true);
        $xAxis = array_values($xAxis);
        $yAxis = array('title' => 'Summery Chart');
        $series = $this->getChartFriendlySeries($filteredStats);
        $config = array(
            'title'=> 'Summery Line Chart'
        );
        return $this->chartHelper->getLineChartJson($series, $xAxis, $yAxis, $config);
    }

    protected function getChartFriendlySeries($filteredStats) {
        $stats = array();
        foreach ($filteredStats as $category => $data) {
            $array = array();
            foreach ($data as $key => $value) {
                $stats[$key][$category] += $value;
                unset($stats[$key][self::TOTAL]);
            }
        }

        foreach ($stats as $lang => $data) {
            $arr = array_values($data);
            $stats[$lang] = $arr;
        }
        return $stats;
    }

    /**
     * Create bean to be assigned for views
     *
     * @param array $filteredStats
     */
    private function createBean($filteredStats) {
        $bean = array(
            'localTotal'     => self::TOTAL,
            'grandTotal'     => $this->grandTotal,
            'filteredStats'  => $filteredStats,
            'translationMap' => $this->translationMap,
            'summeryLineChart' => $this->getSummeryChartJson($filteredStats)
        );
        $this->setBean($bean);
    }
}