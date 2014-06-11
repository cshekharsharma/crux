<?php
/**
 * Creating new user in database
 * @access Command Line
 * @example sudo php CreateNewUser.php --username=johnsmith --password=secret@123
 */

// Setup auto class loader
chdir('../');
require_once 'includes/AutoLoader.php';

if (Utils::isRunningFromCLI()) {
    $cmdArgs = $argv;

    foreach ($cmdArgs as $arg) {
        if (preg_match('/--username=(\w+)/', $arg, $result)) {
            $userName = $result[1];
        }

        if (preg_match('/--password=(\w+)/', $arg, $result)) {
            $password = $result[1];
        }
    }

    // input validations
    if (empty($userName)) Utils::printCLIMessages('No UserName provided, Utils::printCLIMessagesing');
    if (empty($password)) Utils::printCLIMessages('No Password provided, Utils::printCLIMessagesing');
    if (strlen($password) < 3) Utils::printCLIMessages('Password too short, Must have atleast 3 characters');

    $user = new UsersController();
    $response = json_decode($user->createUser($userName, $password), true);
    Utils::printCLIMessages(ucfirst($response['code']).'!! '.$response['msg']);
} else {
    Utils::printCLIMessages('Invalid Access! File is meant to be run from only command line.');
}

exit();