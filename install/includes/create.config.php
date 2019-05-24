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

$admin_name = strip_tags(trim($_POST['admin_name']));
$admin_pass = md5(trim($_POST['admin_pass']));


// get config content
$config = file_get_contents('config.template.php');
$config = str_replace('{$db_host}', $db_host, $config);
$config = str_replace('{$db_user}', $db_user, $config);
$config = str_replace('{$db_pass}', $db_pass, $config);
$config = str_replace('{$db_db}', $db_db, $config);
$config = str_replace('{$db_prefix}', $db_prefix, $config);
$config = str_replace('{$site_url}', $site_url, $config);
$config = str_replace('{$site_dir}', $site_dir, $config);
$config = str_replace('{$time_zone}', $time_zone, $config);
$config = str_replace('{$admin_name}', $admin_name, $config);
$config = str_replace('{$admin_pass}', $admin_pass, $config);


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
$htaccess = file_get_contents('htaccess.template.php');
$htaccess = str_replace('{$base}', $base , $htaccess);

// create file
$file = fopen($site_dir . '/.htaccess', 'w'); // create or open
//$htaccess = "\xEF\xBB\xBF" . $htaccess; // utf encoded
fwrite($file, $htaccess); // write
fclose($file); // close

echo $site_url;


?>