<?php

class IndexView extends AbstractView {

    protected $templateMap = array(
        'INDEX' => 'Index.htpl'
    );

    protected $currModule = 'index';

    public function display() {
        if (!empty($this->viewName)) {
            $bean = $this->getBean();
            if (strtoupper($this->viewName) === 'INDEX') {
                $this->displayProgramList($bean['programList'], $bean['isValidRequest']);
            }
        }
    }

    private function displayProgramList($programList, $isValidRequest) {
        if (!empty($programList)) {
            foreach ($programList as $key => $programData) {
                $descKey = ProgramDetails_DBTable::DESCRIPTION;
                $desc = $programData[$descKey];
                $parsedDesc = Utils::createLinks($desc);
                $programList[$key][$descKey] = $parsedDesc;
            }
            $paginatorInfo = Session::get('PAGINATOR_INFO');
            if ($paginatorInfo['hasNextPage']) {
                $this->smarty->assign('NEXT_PAGE_OFFSET', $paginatorInfo['nextPageOffset']);
            }
            if ($paginatorInfo['hasPrevPage']) {
                $this->smarty->assign('PREV_PAGE_OFFSET', $paginatorInfo['prevPageOffset']);
            }
            $this->smarty->assign('HAS_NEXT_PAGE', $paginatorInfo['hasNextPage']);
            $this->smarty->assign('HAS_PREV_PAGE', $paginatorInfo['hasPrevPage']);
            $this->smarty->assign("PROGRAM_LIST", $programList);
            $this->render(IndexController::MODULE_KEY);
        } else {
            if ($isValidRequest) {
                $this->render(Display::EMPTY_CODEBASE_KEY);
            } else {
                $this->render(Display::NO_ITEM_FOUND_KEY);
            }
        }
    }
}