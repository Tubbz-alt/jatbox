#!/usr/bin/php
<?php
/**
 * stop.php - Creates the "STOP_FILE", which is detected by the start.php script and triggers
 * the termination of the JAT-BOX VM and any other active scripts.
 */
include (__DIR__ . '/../scripts/common.php' );

fopen(STOP_FILE, 'w');