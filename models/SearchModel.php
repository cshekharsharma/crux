<?php

class SearchModel extends AbstractModel {
    
    public function getTextualSuggestions() {
        $userDetails = Session::get(Session::SESS_USER_DETAILS);
        $query = "SELECT title AS name FROM program_details WHERE is_deleted = 0";
        return DBManager::executeQuery($query, array(), true);
    }
    
    public function getMatchedDataset(array $keywords) {
        $query = "SELECT program_details.*, users.user_name, category.name AS category_name, language.name AS language_name
            FROM program_details INNER JOIN category ON category.id = program_details.fk_category INNER JOIN
            language ON language.id = program_details.fk_language INNER JOIN users ON users.id = program_details.fk_created_by
            WHERE program_details.is_deleted = 0 AND (";
        $whereClause = array();
        $bindParam = array();
        foreach ($keywords as $key => $keyword) {
            $whereClause[] = " program_details.title LIKE :searchQuery$key OR program_details.description LIKE :searchQuery$key ";
            $bindParam['searchQuery'.$key] = '%'.$keyword.'%';
        }
        $query .= implode(" OR ", $whereClause) .');';
        $resultSet = DBManager::executeQuery($query, $bindParam, true);
        return $resultSet;
    }
    
    public function getCategoryWiseMatchingDataset($searchString) {
        $keywords = explode(" ", $searchString);
        $query = "SELECT program_details.*, category.name AS category_name, language.name AS language_name, users.user_name
            FROM program_details INNER JOIN category ON category.id = program_details.fk_category INNER JOIN
            language ON language.id = program_details.fk_language INNER JOIN users ON users.id=program_details.fk_created_by
            WHERE program_details.is_deleted = 0 AND (";
        $whereClause = array();
        $bindParam = array();
        foreach ($keywords as $key => $keyword) {
            $whereClause[] = " program_details.fk_category LIKE :searchQuery$key OR category.name LIKE :searchQuery$key ";
            $bindParam['searchQuery'.$key] = '%'.trim($keyword).'%';
        }
        $query .= implode(" OR ", $whereClause) .');';
        $resultSet = DBManager::executeQuery($query, $bindParam, true);
        return $resultSet;
    }
    
    public function getUsersWiseMatchingDataset($searchString) {
        $keywords = explode(" ", $searchString);
        $query = "SELECT program_details.*, category.name AS category_name, language.name AS language_name, users.user_name
            FROM program_details INNER JOIN category ON category.id = program_details.fk_category INNER JOIN
            language ON language.id = program_details.fk_language INNER JOIN users ON users.id=program_details.fk_created_by
            WHERE program_details.is_deleted = 0 AND (";
        $whereClause = array();
        $bindParam = array();
        foreach ($keywords as $key => $keyword) {
            $whereClause[] = " program_details.fk_created_by LIKE :searchQuery$key OR users.user_name LIKE :searchQuery$key ";
            $bindParam['searchQuery'.$key] = '%'.trim($keyword).'%';
        }
        $query .= implode(" OR ", $whereClause) .');';
        $resultSet = DBManager::executeQuery($query, $bindParam, true);
        return $resultSet;
    }
    
    public function getLanguageWiseMatchingDataset($searchString) {
        $keywords = explode(" ", $searchString);
        $query = "SELECT program_details.*, category.name AS category_name, language.name AS language_name, users.user_name
            FROM program_details INNER JOIN category ON category.id = program_details.fk_category INNER JOIN
            language ON language.id = program_details.fk_language INNER JOIN users ON users.id=program_details.fk_created_by
            WHERE program_details.is_deleted = 0 AND (";
        $whereClause = array();
        $bindParam = array();
        foreach ($keywords as $key => $keyword) {
            $whereClause[] = " program_details.fk_language LIKE :searchQuery$key OR language.name LIKE :searchQuery$key ";
            $bindParam['searchQuery'.$key] = '%'.trim($keyword).'%';
        }
        $query .= implode(" OR ", $whereClause) .');';
        $resultSet = DBManager::executeQuery($query, $bindParam, true);
        return $resultSet;
    }
    
}