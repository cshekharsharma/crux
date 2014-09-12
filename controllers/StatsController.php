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

    const CODE_FREQUENCY_PLOT = 'Code frequency plot';
    const CODE_ACCURACY_PLOT = 'Code accuracy plot';
    const CATEGORY_PROGRESS_PLOT = 'Category progress plot';
    const CATEGORY_PIE_PLOT = 'Category pie plot';

    private $grandTotal = 0;
    private $allStats = array();
    private $translationMap = array();

    /**
     * @var ChartHelper
    */
    protected $chartHelper;

    /**
     *
     * @var ProgramDetailsController
     */
    protected $dataProvider;

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

    protected function getCategoryProgressJson($filteredStats) {
        $axis = array();
        $xAxis = (new CategoryController())->getCategoryList(true);
        $axis['xAxis'] = array_values($xAxis);
        $axis['yAxis'] = array('title' => 'Problem Count');
        $series = $this->getSeriesForCategoryProgress($filteredStats);
        $config = array(
            'title'=> self::CATEGORY_PROGRESS_PLOT
        );
        return $this->chartHelper->getLineChartJson($config, $series, $axis);
    }

    protected function getCodeAccuracyJson() {
        $stats = $this->getModel()->getCodeVerificationStats();
        $series = array(
            array('Verified', (int)$stats['verified_count']),
            array('Not Verified', (int)($stats['total'] - $stats['verified_count']))
        );

        $config = array(
            'main_title'  => self::CODE_ACCURACY_PLOT,
            'series_name' => 'Contribution'
        );
        return $this->chartHelper->getPieChartJson($config, $series);
    }

    protected function getCodeFrequencyJson() {
        $frequencyInfo = $this->getModel()->getCodeFrequency();
        $axis['xAxis'] = array_keys($frequencyInfo);
        $axis['yAxis'] = array('title' => 'Problem Count');
        $series = array(
            'Frequency' => array_values($frequencyInfo)
        );
        $config = array(
            'title' => self::CODE_FREQUENCY_PLOT
        );
        return $this->chartHelper->getLineChartJson($config, $series, $axis);
    }

    protected function getSeriesForCategoryProgress($filteredStats) {
        $stats = array();
        foreach ($filteredStats as $category => $data) {
            $array = array();
            foreach ($data as $lang => $value) {
                $stats[$lang][$category] += $value;
                unset($stats[$lang][self::TOTAL]);
            }
        }

        foreach ($stats as $lang => $data) {
            $arr = array_values($data);
            if (!empty($this->translationMap[Language_DBTable::DB_TABLE_NAME][$lang])) {
                $stats[$this->translationMap[Language_DBTable::DB_TABLE_NAME][$lang]] = $arr;
                unset($stats[$lang]);
            } else {
                $stats[$lang] = $arr;
            }
        }
        return $stats;
    }

    /**
     * Get pie chart json for category-pie chart
     * 
     * @return string
     */
    protected function getCategoryPieJson() {
        $stats = $this->getModel()->getCategoryPieStats();
        $series = array();
        $trTable = Category_DBTable::DB_TABLE_NAME;
        foreach ($stats as $key => $value) {
            $series[] = array($this->translationMap[$trTable][$value['category']], (int)$value['count']);
        }
        
        $config = array(
            'main_title'  => self::CATEGORY_PIE_PLOT,
            'series_name' => 'Contribution'
        );
        return $this->chartHelper->getPieChartJson($config, $series);
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
            'codeFrequencyJson' => $this->getCodeFrequencyJson(),
            'codeAccuracyJson' => $this->getCodeAccuracyJson(),
            'categoryProgressJson' => $this->getCategoryProgressJson($filteredStats),
            'categoryPieJson' => $this->getCategoryPieJson()
        );
        $this->setBean($bean);
    }
}