<?php
/**
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class SearchController extends AbstractController {

    const MODULE_KEY = 'search';
    const SEARCH_QUERY_PARAM = "q";

    const NO_RESULT_FOUND = "no_result_found";

    private static $SEARCH_TAGS = array('category', 'language', 'users');

    public function __construct() {
        parent::__construct();
        $this->model = new SearchModel();
        $this->view = new SearchView();
    }

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $searchQuery = RequestManager::getParam(self::SEARCH_QUERY_PARAM);
        if (!empty($searchQuery)) {
            $results = $this->search($searchQuery);
            $this->setBean(
                array(
                    'query' => $searchQuery,
                    'resultSet' => $results
                )
            );
            $this->getView()->setViewName(self::MODULE_KEY)->display();
        } else {
            RequestManager::redirect();
        }
    }

    /**
     * Generic method for performing all search actions
     * 
     * @param string $searchStr
     * @return Ambigous <boolean, mixed>
     */
    private function search($searchStr) {
        $returnSet = array();
        $searchStr = trim($searchStr);
        if (!$this->isTagBasedSearch($searchStr)) {
            $sortedResults = $this->performSimpleSearch($searchStr);
        } else {
            $sortedResults = $this->performTagBasedSearch($searchStr);
        }
        return $sortedResults;
    }

    /**
     * Checks if its a tag based search request
     * 
     * @param string $searchString
     * @return boolean
     */
    private function isTagBasedSearch($searchString) {
        $searchParts = explode(":", $searchString);
        return (count($searchParts) > 1 && in_array(strtolower($searchParts[0]), self::$SEARCH_TAGS));
    }

    /**
     * Perform natural language search in database for given search query
     * 
     * @param string $searchStr
     * @return array $searchResults
     */
    private function performSimpleSearch($searchStr) {
        $keywords = explode(" ", $searchStr);
        $keywords = array_unique($keywords);
        $dataset = $this->getModel()->getMatchedDataset($keywords);
        if (!empty($dataset)) {
            foreach ($dataset as $key => $row) {
                $relevenceScore = 0;
                foreach ($keywords as $keyword) {
                    $keyword = strtolower(trim($keyword));
                    $relevenceScore += (substr_count(strtolower($row['title']), $keyword)) * 2;
                    $relevenceScore += substr_count(strtolower($row['description']), $keyword);
                }
                if ($searchStr === $row['title'] || $searchStr === $row['description']) {
                    $relevenceScore += 20;
                }
                $dataset[$key]['relevenceScore'] = $relevenceScore;
            }
        }
        $sortedResults = $this->getSortedSearchResults($dataset);
        $sortedResults = $this->formatSearchResults($sortedResults, $keywords);
        return $sortedResults;
    }

    /**
     * Perform tag based search resutls for selected keywords, i.e. Users, 
     * language, category
     * 
     * @param string $searchString
     * @return boolean|Ambigous <multitype:, mixed>
     */
    private function performTagBasedSearch($searchString) {
        $searchParts = explode(":", $searchString);
        if (count($searchParts) > 1 && in_array(strtolower($searchParts[0]), self::$SEARCH_TAGS)) {
            $action = $searchParts[0];
            $reqQuery = $searchParts[1];
            $searchResults = $this->getTagBasedSearchResult($action, $reqQuery);
            if (!empty($searchResults)) {
                return $searchResults;
            } else {
                return $this->performSimpleSearch($searchString);
            }
        }
        return $this->performSimpleSearch($searchString);
    }

    /**
     * Sort all search results on the basis of their relevence score to search key
     * 
     * @param array $resultSet
     * @return array
     */
    private function getSortedSearchResults(array $resultSet) {
        uasort($resultSet, function($a, $b) {
            if ($a['relevenceScore'] == $b['relevenceScore']) {
                $aLength = strlen($a['title']) + strlen($a['description']);
                $bLength = strlen($b['title']) + strlen($b['description']);
                if ($aLength == $bLength) {
                    return 0;
                }
                return ($aLength < $bLength) ? -1 : 1;
            }
            return ($a['relevenceScore'] < $b['relevenceScore']) ? 1 : -1;
        });
        return $resultSet;
    }

    /**
     * Provides returnable search result format
     * 
     * @param array $results
     * @param array $keywords
     * @return mixed
     */
    private function formatSearchResults(array $results, $keywords) {
        foreach ($results as $key => $row) {
            $titleCol = ProgramDetails_DBTable::TITLE;
            $descCol = ProgramDetails_DBTable::DESCRIPTION;
            $row[$titleCol] = $this->highlightMatchingText($row[$titleCol], $keywords);
            $row[$descCol] = $this->highlightMatchingText($row[$descCol], $keywords);
            $results[$key] = $row;
        }
        return $results;
    }

    /**
     * Return highlighted search results
     * 
     * @param string $haystack
     * @param array $keywords
     * @return array
     */
    private function highlightMatchingText($haystack, array $keywords) {
        $pre = '<span class="search-highlight">';
        $post = '</span>';
        foreach ($keywords as $keyword) {
            $keyword = preg_quote(trim($keyword));
            $haystack = preg_replace("/($keyword)/i", "$pre$1$post", $haystack);
        }
        return $haystack;
    }

    /**
     * Get tag based search results
     * 
     * @param unknown $tag
     * @param unknown $searchString
     * @return boolean
     */
    private function getTagBasedSearchResult($tag, $searchString) {
        $methodName = 'get' . ucfirst($tag) . 'WiseMatchingDataset';
        if (method_exists($this->getModel(), $methodName)) {
            return $this->getModel()->$methodName($searchString);
        }
        return false;
    }

    /**
     * Get autocomplete search suggestions for search box
     * 
     * @return string
     */
    public function getSearchSuggestions() {
        $searchSuggestions = array();
        $userList = ResourceProvider::getControllerByResourceKey(UsersController::MODULE_KEY)->getUserList();
        $languageList = ResourceProvider::getControllerByResourceKey(LanguageController::MODULE_KEY)->getLanguageList();
        $categoryList = ResourceProvider::getControllerByResourceKey(CategoryController::MODULE_KEY)->getCategoryList();
        $textSuggestions = $this->getModel()->getTextualSuggestions();
        $suggestions = array(
            UsersController::MODULE_KEY => $userList,
            LanguageController::MODULE_KEY => $languageList,
            CategoryController::MODULE_KEY => $categoryList,
            ExplorerController::MODULE_KEY => $textSuggestions
        );

        foreach ($suggestions as $type => $dataset) {
            foreach ($dataset as $key => $array) {
                $arrayKey = empty($array['name']) ? 'user_name' : 'name';
                if (in_array($type, self::$SEARCH_TAGS)) {
                    $searchSuggestions[] = $type.':'.$array[$arrayKey];
                } else {
                    $searchSuggestions[] = $array[$arrayKey];
                }
            }
        }
        return json_encode($searchSuggestions);
    }
}