<?php

class StatsView extends AbstractView {

    protected $templateMap = array(
        'STATS' => 'showStats.htpl'
    );

    protected $currModule = 'stats';

    public function display() {
        $bean = $this->getBean();
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displayStatsSummary($bean);
            }
        }
    }

    private function displayStatsSummary($bean) {
        $this->smarty->assign('STATS', $bean['filteredStats']);
        $this->smarty->assign('TOTAL_STR', $bean['localTotal']);
        $this->smarty->assign('GRAND_TOTAL', $bean['grandTotal']);
        $this->smarty->assign('LANG_MAP', $bean['translationMap'][Language_DBTable::DB_TABLE_NAME]);
        $this->smarty->assign('CATE_MAP', $bean['translationMap'][Category_DBTable::DB_TABLE_NAME]);
        $this->smarty->assign('CATEGORY_PROGRESS', $bean['categoryProgressJson']);
        $this->smarty->assign('CODE_ACCURACY', $bean['codeAccuracyJson']);
        $this->render($this->currModule);
    }
}