<?php
/*** 
/* Program : Webwon Config
/* Author : Won Song
/* Description : Webwon config file
 ***/

// Start the session 
session_start();
$_SESSION['woncms_sessid'] ='123456789';

// Set default time zone
date_default_timezone_set('US/Eastern');

// Set time limit to 0
set_time_limit(0);

// Setup Config
Won::set(new Config());
Won::get('Config')->site_url 			= 'http://www.won.com/webwon/src2.0';
Won::get('Config')->site_dir 			= 'H:/Websites/webwon/src2.0';
Won::get('Config')->content_url 		= 'http://www.won.com/webwon/src2.0/contents';
Won::get('Config')->content_dir 		= 'H:/Websites/webwon/src2.0/contents';
Won::get('Config')->admin_url 			= 'http://www.won.com/webwon/src2.0/admin';
Won::get('Config')->admin_dir 			= 'H:/Websites/webwon/src2.0/admin_site';
Won::get('Config')->admin_content_url 	= 'http://www.won.com/webwon/src2.0/admin_site/contents';
Won::get('Config')->admin_content_dir 	= 'H:/Websites/webwon/src2.0/admin_site/contents';
Won::get('Config')->module_url 			= 'http://www.won.com/webwon/src2.0/modules';
Won::get('Config')->module_dir 			= 'H:/Websites/webwon/src2.0/modules';
Won::get('Config')->include_dir			= 'H:/Websites/webwon/src2.0/contents/includes';
Won::get('Config')->loaded				= true;

// Setup Sql
Won::set(new DB());
Won::get('DB')->connect_sql('localhost', 'root', '777', 'webwon');
Won::get('DB')->prefix = 'won_';

// Autoload Classes
function __autoload($class_name)
{
	$module_class	= 'H:/Websites/webwon/src2.0/modules/' . $class_name . '/' . $class_name . '.php';
	$core_class 	= 'H:/Websites/webwon/src2.0/modules/Core/'  . $class_name . '.php';
	
	if (file_exists($module_class))		
		require $module_class;	
	
	elseif (file_exists($core_class))	
		require $core_class;	
	
	else
	{
		throw new ErrorException($class_name . ' Module is Missing.');
		exit();
	}
}
?>