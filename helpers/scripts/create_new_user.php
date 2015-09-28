<?php
/**
 * Creating new user in database
 *
 * @access Command Line
 * @example sudo php create_new_user.php --username=johnsmith --password=secret@123
 */

chdir('../../');
// include auto class loader
require_once 'includes/AutoLoader.php';

if (Utils::isRunningFromCLI()) {
    $cmdArgs = $argv;
    $userName = $password = '';
    foreach ($cmdArgs as $arg) {
        if (preg_match('/--username=(\w+)/', $arg, $result)) {
            $userName = $result[1];
        }

        if (preg_match('/--password=(\w+)/', $arg, $result)) {
            $password = $result[1];
        }
    }

    // input validations
    if (empty($userName)) { 
        Utils::printCLIMessages('No UserName provided, Retry!');
        exit;
    }
    if (empty($password)) {
        Utils::printCLIMessages('No Password provided, Retry!');
        exit;
    }
    if (strlen($password) < 3) {
        Utils::printCLIMessages('Password too short, Must have atleast 3 characters');
        exit;
    }
    $user = new UsersController();
    $response = json_decode($user->createUser($userName, $password), true);
    Utils::printCLIMessages(ucfirst($response['code']).'!! '.$response['msg']);
} else {
    Utils::printCLIMessages('Invalid Access! File is meant to be run from only command line.');
}

exit();
