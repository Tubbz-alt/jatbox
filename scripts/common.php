<?php
/**
 * Functions and constants used in multiple JAT-Box scripts
 */

// OS_* - legal return values of the get_os() function
const OS_WIN = 1;
const OS_LINUX = 2;
const OS_MAC = 3;

// STOP_FILE - the path to a file that, if present, will trigger the JAT-Box VM to terminate and the Selenium
// Node (if running) to be stopped (define() used instead of const for PHP 5.3/5.4 support.
define('STOP_FILE', __DIR__ . '/../bin/jatbox_stop');

/**
 * Determines what OS family the script is running in
 * @return int One of the OS_* constants
 * @throws \Exception
 */
function get_os () {
    $uname = php_uname('s');
    if ($uname == "Darwin") {
        return OS_MAC;
    }
    if ($uname == "Linux") {
        return OS_LINUX;
    }
    if (strtoupper(substr($uname, 0, 3)) === 'WIN') {
        return OS_WIN;
    }

    throw new \Exception("Did not recognize operating system. uname: '$uname'");
}

function do_exec ($command) {
    echo "executing: $command";

    $p = popen($command, 'r');
    while (!feof($p))
    {
        echo fread($p, 4096);
        @ flush();
    }
}

function exec_in_background($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r"));
    }
    else {
        exec($cmd . " > /dev/null &");
    }
}