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

// Setup Config
Won::set(new Config());
Won::get('Config')->site_url 			= '{$site_url}';
Won::get('Config')->site_dir 			= '{$site_dir}';
Won::get('Config')->content_url 		= '{$site_url}/contents';
Won::get('Config')->content_dir 		= '{$site_dir}/contents';
Won::get('Config')->admin_url 			= '{$site_url}/admin';
Won::get('Config')->admin_dir 			= '{$site_dir}/admin_site';
Won::get('Config')->admin_content_url 	= '{$site_url}/admin_site/contents';
Won::get('Config')->admin_content_dir 	= '{$site_dir}/admin_site/contents';
Won::get('Config')->module_url 			= '{$site_url}/modules';
Won::get('Config')->module_dir 			= '{$site_dir}/modules';
Won::get('Config')->include_dir			= '{$site_dir}/contents/includes';
Won::get('Config')->loaded				= true;

// Setup Sql
Won::set(new DB());
Won::get('DB')->connect_sql('{$db_host}', '{$db_user}', '{$db_pass}', '{$db_db}');
Won::get('DB')->prefix = '{$db_prefix}';

// Autoload Classes
function __autoload(\$class_name)
{
	\$module_class	= '{$site_dir}/modules/' . \$class_name . '/' . \$class_name . '.php';
	\$core_class 	= '{$site_dir}/modules/Core/'  . \$class_name . '.php';
	
	if (file_exists(\$module_class))		
		require \$module_class;	
	
	elseif (file_exists(\$core_class))	
		require \$core_class;	
	
	else
	{
		throw new ErrorException(\$class_name . ' Module is Missing.');
		exit();
	}
}
?>
CON;
?>