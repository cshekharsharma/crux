<?php
/**
 * DBTable for User Preferences
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package dbtables
 * @since Jun 14, 2014
 */
class UserPreferences_DBTable extends AbstractDBTable {

    const DB_TABLE_NAME = 'user_preferences';

    const RECORD_ID = "id";
    const USER_ID = 'user_id';
    const CONTENTS = "contents";
    const CREATED_ON = "created_on";
    const MODIFIED_ON = "modified_on";
    const IS_DELETED = "is_deleted";
}