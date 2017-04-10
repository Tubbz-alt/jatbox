#!/usr/bin/php
<?php

##
# This file is part of the JAT-Box project
#
# Starts the Selenium Server hub, which can then have any number of nodes
# registered to it
#
# This needs to run on the MASTER machine (the Vagrant VM). Nodes can be registered
# by running ./node.php on the SLAVE machines that will execute tests in-browser.
#
################################################################################
require_once('Selenium.php');
Selenium::launchHub();