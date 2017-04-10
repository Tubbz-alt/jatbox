<?php
/**
 * Call this script to stop the Vagrant server
 */
shell_exec('php /vagrant/bin/stop.php');

echo "System is halting. You may close this window.";