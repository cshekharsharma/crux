<?php


/**
 * Installs the app
 */

// Including autoloader
chdir('../');
require_once 'includes/AutoLoader.php';

if (Utils::isRunningFromCLI()) {
    Utils::printCLIMessages('Coudn\'t run install script, Please follow instructions in INSTALL manual');
    // TODO: Check if db schema exists and import into database
} else {
    Utils::printCLIMessages('Invalid Access! File is meant to be run from only command line'    );
}

exit();