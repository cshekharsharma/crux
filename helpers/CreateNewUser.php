<?php
/**
 * Creating new user in database
 * @access Command Line
 * @example sudo php CreateNewUser.php --username=johnsmith --password=secret@123
 */

// Setup auto class loader
chdir('../');
require_once 'includes/AutoLoader.php';

if (php_sapi_name() === 'cli') {
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
    if (empty($userName)) exit('No UserName provided, Exiting'.PHP_EOL);
    if (empty($password)) exit('No Password provided, Exiting'.PHP_EOL);
    if (strlen($password) < 3) exit('Password too short, Must have atleast 3 characters'.PHP_EOL);

    $user = new UsersController();
    $response = json_decode($user->createUser($userName, $password), true);
    exit(ucfirst($response['code']).'!! '.$response['msg'].PHP_EOL);
} else {
    exit('Invalid Access! File is meant to be run from only command line.'.PHP_EOL);
}