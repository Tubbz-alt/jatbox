#!/usr/bin/php
<?php
##
# This file is part of the JAT-Box project
#
# node: This script can be used on a slave machine to connect it to the server
# machine and allow the slave machine to run browser-dependent tests.
################################################################################

require_once('Selenium.php');

# Param 1: A browser argument like "-browser browserName=chrome,maxInstances=3 -browser browserName=firefox,maxInstances=3"
$browserArgs = array_key_exists('1', $argv) ? $argv[1] : "-browser browserName=chrome -browser browserName=firefox";

# Determine what system we are running in. We currently don't test for 32 vs 64 bit systems - we just assume Mac is 64
# bit, and Windows/Linux are 32. That's just a pragmatic decision - if there is a need we can add additional drivers
# and add sniffing for 32 vs 64 bit.
$uname = php_uname('s');
if ( $uname == 'Darwin' ) {
    $system = "mac64";
} elseif ( $uname = 'Linux' ) {
    $system = 'linux32';
} else {
    # For the time being we are simply assuming you're in Windows if one of the above cases doesn't apply.
    $system = 'win32';
}

Selenium::launchNode($system, $browserArgs);