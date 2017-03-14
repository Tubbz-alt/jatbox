#!/usr/bin/php
<?php
    require_once (__DIR__ . '/../scripts/common.php');

    // WORKING_DIR is really a constant but we define it as a variable for PHP<5.6 support.
    $WORKING_DIR = __DIR__ . '/..';

    # Cleanup function that runs when this script exits. Normally this will be after the stop file (see the loop at bottom)
    # has been created to signal the script to end, but we bind it to run on exit so that the script will clean up after
    # itself if it's terminated unexpectedly
    register_shutdown_function(function () use ($WORKING_DIR){
        if ( file_exists(STOP_FILE) ) {
            unlink(STOP_FILE);
        }

        chdir($WORKING_DIR);
        do_exec('vagrant halt');
        do_exec('pkill -f "selenium-server"');
    });

    chdir($WORKING_DIR);

    # Start the VM that will run our tests
    do_exec('vagrant up');

//    # Start the local Selenium node for running local tests
//    include (WORKING_DIR . '/selenium/node.php');

    if (get_os() == OS_WIN) {
        do_exec('cmd /c start http://192.168.33.11/');
    } else {
        do_exec('open http://192.168.33.11/');
    }

    while ( ! file_exists(STOP_FILE)) {
        sleep(.1);
    }