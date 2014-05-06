<?php

class ProgramDetails_DBTable extends AbstractDbTable {

    const DB_TABLE_NAME = 'program_details';

    const PROGRAM_ID = 'id';
    const TITLE = 'title';
    const FK_LANGUAGE_ID = 'fk_language';
    const FK_CATEGORY_ID = 'fk_category';
    const ACTUAL_FILE_NAME = 'actual_file_name';
    const STORED_FILE_NAME = 'stored_file_name';
    const LEVEL = 'level';
    const DESCRIPTION = 'description';
    const IS_VERIFIED = 'is_verified';
    const CREATED_ON = 'created_on';
    const UPDATED_ON = 'updated_on';
    const FK_CREATED_BY = 'fk_created_by';
    const IS_DELETED = 'is_deleted';
}