<?php
/**
 * Call this script to execute all Behat features
 */
while (@ ob_end_flush()); // end all output buffers if any

// Execute the behat test and output the results as they are generated
$proc = popen('/vagrant/vendor/bin/behat --config=/vagrant/behat.yml 2>&1', 'r');
echo '<pre>';
while (!feof($proc))
{
    echo fread($proc, 4096);
    @ flush();
}
echo '</pre>';
echo '<a href="/">Back</a>';