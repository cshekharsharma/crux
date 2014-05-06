<?php

class IndexController extends BaseController {

    const MODULE_KEY = 'index';

    private $isHomePage = false;

    public function run(Resource $resource) {
        Logger::getLogger()->LogInfo("Serving Index Controller");
        $uriParams = $resource->getParams();
        $this->displayProgramList($uriParams);
    }

    public function displayProgramList($inputParams) {
        $programs = array();
        $lang = $inputParams[RequestManager::INPUT_PARAM_LANG];
        $category = $inputParams[RequestManager::INPUT_PARAM_CATE];
        $programs = $this->getProgramList($lang, $category);
        $markup = $this->populateTemplateMarkup($programs);
    }

    private function getProgramList($lang = false, $category = false) {
        $programList = array();
        $bindParams = array();
        if (empty($lang) && empty($category)) {
            $this->isHomePage = true;
        }
        $pidCol = ProgramDetails_DBTable::PROGRAM_ID;
        $langCol = ProgramDetails_DBTable::FK_LANGUAGE_ID;
        $cateCol = ProgramDetails_DBTable::FK_CATEGORY_ID;
        $query = 'SELECT '.ProgramDetails_DBTable::DB_TABLE_NAME.'.*,'.
            Users_DBTable::DB_TABLE_NAME.'.'.Users_DBTable::USER_NAME.' AS created_by,'.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_NAME.' AS category_name,'.
            Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_NAME.' AS language_name FROM '.
            ProgramDetails_DBTable::DB_TABLE_NAME.' INNER JOIN '.Category_DBTable::DB_TABLE_NAME.' ON '.
            Category_DBTable::DB_TABLE_NAME.'.'.Category_DBTable::CATEGORY_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID.' INNER JOIN '.
            Language_DBTable::DB_TABLE_NAME.' ON '.Language_DBTable::DB_TABLE_NAME.'.'.Language_DBTable::LANGUAGE_ID.' = '.
            ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID.' INNER JOIN '.
            Users_DBTable::DB_TABLE_NAME.' ON '.Users_DBTable::DB_TABLE_NAME.'.'.Users_DBTable::USER_ID.' = '.
            ProgramDetails_DBTable::FK_CREATED_BY.' WHERE ';
        if (!empty($lang)) {
            $bindParams[] = $lang;
            $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_LANGUAGE_ID."=? AND ";
            if (!empty($category)) {
                $bindParams[] = $category;
                $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::FK_CATEGORY_ID."=? AND ";
            }
        }
        $query .= ProgramDetails_DBTable::DB_TABLE_NAME.'.'.ProgramDetails_DBTable::IS_DELETED."= '0'";
        $resultSet = DBManager::executeQuery($query, $bindParams, true);
        return $resultSet;
    }

    private function populateTemplateMarkup($programList) {
        if (!empty($programList)) {
            foreach ($programList as $key => $programData) {
                $descKey = ProgramDetails_DBTable::DESCRIPTION;
                $desc = $programData[$descKey];
                $parsedDesc = Utils::createLinks($desc);
                $programList[$key][$descKey] = $parsedDesc;
            }
            $this->smarty->assign("PROGRAM_LIST", $programList);
            $this->smarty->display("string:". Display::render("INDEX"));
        } else {
            if ($this->isHomePage) {
                $this->smarty->display("string:". Display::render("EMPTY_CODEBASE"));
            } else {
                $this->smarty->display("string:". Display::render("NO_ITEM_FOUND"));
            }
        }
    }
}