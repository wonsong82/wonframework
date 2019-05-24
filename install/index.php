<?php
/**
 * @package Installer
 * @name index.php
 * @author Won Song
 */

// check if config is already setup
// display installed template if config is already configured.
$index = file_exists('../config.php')? 
	'installed.php' : 'install.php';

require 'templates/' . $index;
