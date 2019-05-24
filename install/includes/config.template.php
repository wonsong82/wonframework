<?php
$config_tpl = <<<CON
<?php
/*** 
/* Program : Webwon Config
/* Author : Won Song
/* Description : Webwon config file
 ***/

// Start the session 
session_start();

// Set default time zone
date_default_timezone_set('{$time_zone}');

// Set time limit to 0
set_time_limit(0);

// Administrator Info
define('ADMIN_NAME', '{$admin_name}');
define('ADMIN_PASS', '{$admin_pass}');

// Site definitions.

// Website URL & DIR
define('SITE_URL', '{$site_url}');
define('SITE_DIR', '{$site_dir}');

// Module URL & DIR
define('MODULE_URL', SITE_URL . '/modules');
define('MODULE_DIR', SITE_DIR . '/modules');

// Content URL & DIR
define('CONTENT_URL',  SITE_URL . '/contents');
define('CONTENT_DIR', SITE_DIR . '/contents');

// Content Includes DIR
define('INCLUDE_DIR', CONTENT_DIR . '/includes');

// Admin URL & DIR
define('ADMIN_URL', SITE_URL . '/admin');
define('ADMIN_DIR', SITE_DIR . '/admin');

// SQL Initialize
Sql::connect('{$db_host}', '{$db_user}', '{$db_pass}', '{$db_db}');
Sql::prefix('{$db_prefix}');

// Auto-load modules
function __autoload(\$class_name)
{
	\$class_file =  MODULE_DIR . '/' . \$class_name . '/' . \$class_name . '.php';
	
	if (file_exists(\$class_file))
	{	
		require_once \$class_file;
	}
	
	else
	{
		echo \$class_name . ' module is missing.';
		exit();
	}
}

define('CONFIG_LOADED',1);
?>
CON;
?>