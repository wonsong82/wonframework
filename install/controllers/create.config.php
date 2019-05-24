<?php
// get the posts and trim
$db_host = trim($_POST['db_host']);
$db_user = trim($_POST['db_user']);
$db_pass = trim($_POST['db_pass']);
$db_db = trim($_POST['db_db']);
$db_prefix = trim($_POST['db_prefix']);

$site_url = strtolower(rtrim(trim($_POST['site_url']), '/'));
$time_zone = trim($_POST['time_zone']);

$admin_name = strip_tags(trim($_POST['admin_name']));
$admin_pass = md5(trim($_POST['admin_pass']));

// Define $root_dir
$site_dir = str_replace('/install/controllers/create.config.php', '', $_SERVER['SCRIPT_FILENAME']);

// Generate Config String
$config = <<<CONFIG
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

// Database configurations
define('DB_HOST', '{$db_host}');
define('DB_USER', '{$db_user}');
define('DB_PASS', '{$db_pass}');
define('DB_DB', '{$db_db}');
define('DB_PREFIX', '{$db_prefix}');

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
define('CONTENT_URL', SITE_URL . '/contents');
define('CONTENT_DIR', SITE_DIR . '/contents');

// Admin URL & DIR
define('ADMIN_URL', SITE_URL . '/admin');
define('ADMIN_DIR', SITE_DIR . '/admin');

// SQL Initialize
\$sql = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_DB) or die("Cannot connect to the database");
\$sql->set_charset('utf8');

// Auto-load modules
function __autoload(\$class_name)
{
	\$class_file = MODULE_DIR . '/' . \$class_name . '/' . \$class_name . '.php';
	
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
CONFIG;


// create file
$file = fopen($site_dir . '/config.php', 'w');

// make the file UTF Encoded
$config = "\xEF\xBB\xBF" . $config;

fwrite($file, $config);
fclose($file);

/////////////////////////////////////////////////////////////////////////////////////////


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


$htaccess = <<<HTACCESS
<IfModule mod_rewrite.c>
SetEnv HTTP_MOD_REWRITE On
RewriteEngine On
#RewriteBase {$base}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$base}index.php [L]
</IfModule>
HTACCESS;

// create file
$file = fopen($site_dir . '/.htaccess', 'w');


// make the file UTF Encoded
$htaccess = "\xEF\xBB\xBF" . $htaccess;

fwrite($file, $htaccess);
fclose($file);

echo $site_url;

?>