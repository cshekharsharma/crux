<?php

class Users_DBTable extends AbstractDbTable {

    const DB_TABLE_NAME = 'users';

    const USER_ID = 'id';
    const USER_NAME = 'user_name';
    const USER_HASH = 'user_hash';
    const IS_LOGGED_IN = "is_loggedin";
    const SESSION_ID = "session_id";
    const IP_ADDRESS = "ip_address";
    const LAST_LOGIN = "last_login";
    const CREATED_ON = "created_on";
    const IS_ACTIVE = "is_active";
    const IS_DELETED = "is_deleted";
}