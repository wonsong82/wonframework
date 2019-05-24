<?php

// get the posts and trim
$db_host = trim($_POST['db_host']);
$db_user = trim($_POST['db_user']);
$db_pass = trim($_POST['db_pass']);
$db_db = trim($_POST['db_db']);
$db_prefix = trim($_POST['db_prefix']);

$site_url = strtolower(rtrim(trim($_POST['site_url']), '/'));
$site_dir = str_replace('/install/includes/create.config.php', '', $_SERVER['SCRIPT_FILENAME']);

$time_zone = trim($_POST['time_zone']);

$admin_username = strip_tags(trim($_POST['admin_username']));
$admin_password = trim($_POST['admin_password']);
$admin_name = strip_tags(trim($_POST['admin_name']));
$admin_email = trim($_POST['admin_email']);


// get config content
include 'config.template.php';
$config = $config_tpl;
$config = str_replace('{$db_host}', $db_host, $config);
$config = str_replace('{$db_user}', $db_user, $config);
$config = str_replace('{$db_pass}', $db_pass, $config);
$config = str_replace('{$db_db}', $db_db, $config);
$config = str_replace('{$db_prefix}', $db_prefix, $config);
$config = str_replace('{$site_url}', $site_url, $config);
$config = str_replace('{$site_dir}', $site_dir, $config);
$config = str_replace('{$time_zone}', $time_zone, $config);


// create config file
$file = fopen($site_dir . '/config.php', 'w'); // create or open config.php
//$config = "\xEF\xBB\xBF" . $config; // make the file UTF Encoded
fwrite($file, $config); // write to config file
fclose($file); // close

// get base
preg_match('#^https?#i', $site_url, $protocol);
$protocol = $protocol[0].'://';
$sub_url = explode('/' , str_replace($protocol, '', $site_url));

if (count($sub_url) > 1)
{
	array_shift($sub_url);
	$base = '/'.implode('/', $sub_url).'/';
}
else
	$base = '/';

// get htaccess content
include 'htaccess.template.php';

$htaccess = $htaccess_tpl;
$htaccess = str_replace('{$base}', $base , $htaccess);

// create file
$file = fopen($site_dir . '/.htaccess', 'w'); // create or open
//$htaccess = "\xEF\xBB\xBF" . $htaccess; // utf encoded
fwrite($file, $htaccess); // write
fclose($file); // close


// add Admin User
require '../../config.php';
Won::set(new User());
Won::get('User')->add_user($admin_username, $admin_password, $admin_name, $admin_email, true, false);
Won::get('User')->add_group('Administrator', false);
Won::get('User')->add_group('Member');
Won::get('User')->add_user_to_group($admin_username, 'Administrator');
Won::get('User')->add_user_to_group($admin_username, 'Member');

?>
<a href="<?=$site_url?>/admin">here</a>