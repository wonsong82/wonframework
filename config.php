﻿<?php
/*** 
/* Program : Webwon Config
/* Author : Won Song
/* Description : Webwon config file
 ***/

// Start the session 
session_start();

// Set default time zone
date_default_timezone_set('US/Eastern');

// Set time limit to 0
set_time_limit(0);

// Administrator Info
define('ADMIN_NAME', 'admin');
define('ADMIN_PASS', 'f1c1592588411002af340cbaedd6fc33');

// Site definitions.

// Website URL & DIR
define('SITE_URL', 'http://www.won.com/webwon/src1.1');
define('SITE_DIR', 'H:/Websites/webwon/src1.1');

// Module URL & DIR
define('MODULE_URL', SITE_URL . '/modules');
define('MODULE_DIR', SITE_DIR . '/modules');

// Content URL & DIR
define('CONTENT_URL',  SITE_URL . '/contents');
define('CONTENT_DIR', SITE_DIR . '/contents');

// Admin URL & DIR
define('ADMIN_URL', SITE_URL . '/admin');
define('ADMIN_DIR', SITE_DIR . '/admin');

// SQL Initialize
Sql::connect('localhost', 'root', '777', 'webwon');
Sql::prefix('won_');

// Auto-load modules
function __autoload($class_name)
{
	$class_file =  MODULE_DIR . '/' . $class_name . '/' . $class_name . '.php';
	
	if (file_exists($class_file))
	{	
		require_once $class_file;
	}
	
	else
	{
		echo $class_name . ' module is missing.';
		exit();
	}
}

define('CONFIG_LOADED',1);
?>