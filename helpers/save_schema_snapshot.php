<?php

/**
 * Take dump for database selectively (schema and data) and store on appropriate dir
 */

// Including autoloader
chdir('../');
require_once 'includes/AutoLoader.php';

if (Utils::isRunningFromCLI()) {
    
    $schemaDumpPath = Constants::DATA_DIR.'database/schema-dump.sql';
    $dataDumpPath = Constants::DATA_DIR.'database/data-dump.sql';
    $finalDumpPath = Constants::DATA_DIR.'database/dbschema-latest.sql';

    $schemaOnly = array('program_details', 'users', 'user_preferences');
    $dataNschema = array('category', 'language');

    $dumpCommand = 'mysqldump -u '.Configuration::get(Configuration::DB_USER);
    $dumpCommand .=' -p'.Configuration::get(Configuration::DB_PASS);
    $dumpCommand .=' -h '.Configuration::get(Configuration::DB_HOST);
    $dumpCommand .= ' '.Configuration::get(Configuration::DB_NAME);

    // Schema Dump Cmd
    $schemaDumpCmd = $dumpCommand.' '.implode(' ', $schemaOnly).' --no-data > '.$schemaDumpPath;
    shell_exec($schemaDumpCmd);
    // Data Dump Cmd
    $dataDumpCmd = $dumpCommand.' '.implode(' ', $dataNschema).' > '.$dataDumpPath;
    shell_exec($dataDumpCmd);

    $contents = file_get_contents($schemaDumpPath);
    $contents .= '';
    $contents .= file_get_contents($dataDumpPath);
    if (!empty($contents)) {
        unlink($schemaDumpPath);
        unlink($dataDumpPath);
        if (file_exists($finalDumpPath)) {
            unlink($finalDumpPath);
        }
        
        if (file_put_contents($finalDumpPath, $contents)) {
            Utils::printCLIMessages('Backup successfully saved in '.$finalDumpPath);
        } else {
            Utils::printCLIMessages('Something went wrong! Backup operation failed, Retry');
        }
    } else {
        Utils::printCLIMessages('Nothing to write! Backup operation failed, Retry');
    }
} else {
    Utils::printCLIMessages('Invalid Access! File is meant to be run from only command line.');
}

exit();
