<?php

class SearchView extends AbstractView {

    protected $templateMap = array(
        'SEARCH' => 'searchResults.htpl'
    );

    protected $currModule = 'search';

    public function display() {
        $bean = $this->getBean();
        if (!empty($this->viewName)) {
            if (strtoupper($this->viewName) === strtoupper($this->currModule)) {
                $this->displaySearchResults($this->getBean());
            }
        }
    }
    
    private function displaySearchResults($bean) {
        $query = $bean['query'];
        $resultSet = $bean['resultSet'];
        $totalResults = (!empty($resultSet)) ? count($resultSet) : 0;
        if (!empty($resultSet)) {
            $this->smarty->assign("RESULT_SET", $resultSet);
        } else {
            $noResult = SearchController::NO_RESULT_FOUND;
            $this->smarty->assign($noResult, $noResult);
        }
        $this->smarty->assign("totalResults", $totalResults);
        $this->smarty->assign("SEARCH_KEY", htmlentities($query, ENT_QUOTES));
        $this->render($this->currModule);
    }
    
}