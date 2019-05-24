<?php
// Webwon Installation

// defines

define('PROTOCOL', isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'?'https://':'http://');
define('INSTALL_URL', strtolower(PROTOCOL . $_SERVER['HTTP_HOST'] . str_replace('index.php','', $_SERVER['SCRIPT_NAME'])));
define('SITE_URL', str_replace('install/','',INSTALL_URL));
define('SITE_DIR' , dirname(dirname(__FILE__)). '/');
define('INSTALL_DIR' , SITE_DIR . 'install/');
define('SYSTEM_DIR' , SITE_DIR . 'system/');
define('PARTS_DIR' , INSTALL_DIR . 'parts/');
define('CHARSET', 'utf8');
define('COLLATE', 'utf8_general_ci');

// Check for Install
if(file_exists(SITE_DIR . 'config.php')){
	$reg=new stdclass;
	require SITE_DIR . 'config.php';
	header('Location:'. $reg->config->admin);
	exit();
}

// Load Required Packages
require SYSTEM_DIR . 'com.won/auth/PHPass.php';
require SYSTEM_DIR . 'com.won/server/ServerInfo.php';
require SYSTEM_DIR . 'com.won/util/Timezone.php';
require SYSTEM_DIR . 'com.won/database/MySQL.php';

// Validate Requirements
$checker = new \com\won\server\ServerInfo();
$check['PHP Verson'] = $checker->checkPHPVersion();
$check['MySQL Version'] = $checker->checkMySQLVersion();
$check['Apache Requirements'] = $checker->checkApache();
$check['PHP Requirements'] = $checker->checkPHP();
$checkPassed = true;
foreach($check as $checkField){
	if(!$checkField['status']){
		$checkPassed = false;
	}
}


// Validate DB Inputs
$db['hostname'] = isset($_POST['db_hostname'])&&!empty($_POST['db_hostname'])&&trim($_POST['db_hostname'])!=''? trim($_POST['db_hostname']):'';
$db['username'] = isset($_POST['db_username'])&&!empty($_POST['db_username'])&&trim($_POST['db_username'])!=''? trim($_POST['db_username']):'';
$db['password'] = isset($_POST['db_password'])&&!empty($_POST['db_password'])&&trim($_POST['db_password'])!=''? trim($_POST['db_password']):'';
$db['database'] = isset($_POST['db_database'])&&!empty($_POST['db_database'])&&trim($_POST['db_database'])!=''? trim($_POST['db_database']):'';
$dbPassed = true;
if($db['hostname'] == ''){
	$dbPassed = false;
}else{
	@$sql = new MySQLi($db['hostname'], $db['username'], $db['password'], $db['database']);
	if($sql->connect_error){
		$dbPassed = false;
	} else {
		$dbExist=$sql->query("SELECT `SCHEMA_NAME` FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$db['database']}'");
		if(!$dbExist->num_rows)
			$dbPassed = false;
	}
}

// Validate DB Handler Inputs
$dbhandlerPassed = true;
$dbhandler['uri']['name'] = 'DB Handler URI';
$dbhandler['uri']['val'] = isset($_POST['dbhandler_uri'])&&!empty($_POST['dbhandler_uri'])&&trim($_POST['dbhandler_uri'])!=''? strtolower(rtrim(trim($_POST['dbhandler_uri']),'/')).'/' : 'dbmanager/';
$dbhandler['uri']['regex'] = '#^[a-z\_\-]{5,}/?$#';
$dbhandler['uri']['error'] = 'Wrong URI format.';
$dbhandler['password']['name'] = 'DB Handler Password';
$dbhandler['password']['val'] = isset($_POST['dbhandler_password'])&&!empty($_POST['dbhandler_password'])&&trim($_POST['dbhandler_password'])!=''? trim($_POST['dbhandler_password']) : '';
$dbhandler['password']['error'] = 'Password must be 3-15 chars. And some special chars are not allowed.'; 
$dbhandler['password']['regex'] = '#^[a-zA-Z0-9\_\-\/\?\!]{3,15}$#';
foreach($dbhandler as &$dbhandlerField){
	if($dbhandlerField['val']=='' || !preg_match($dbhandlerField['regex'],$dbhandlerField['val'])){
		$dbhandlerPassed = false;
		$dbhandlerField['status'] = false;
	} else
		$dbhandlerField['status'] = true;
}

// Validate Site Inputs
$sitePassed = true;
$site['url']['name'] = 'Site Address(URL, w/ www)';
$site['url']['val'] = isset($_POST['site_url'])&&!empty($_POST['site_url'])&&trim($_POST['site_url'])!=''? strtolower(rtrim(trim($_POST['site_url']),'/')).'/' : SITE_URL;
$site['url']['error'] = 'Must include http or https';
$site['url']['regex'] = '#^https?://#';
$site['admin']['name'] = 'Site Admin URI';
$site['admin']['val'] = isset($_POST['site_admin'])&&!empty($_POST['site_admin'])&&trim($_POST['site_admin'])!=''? strtolower(rtrim(trim($_POST['site_admin']),'/')).'/' : 'admin/';
$site['admin']['regex'] = '#^[a-z\_\-]{5,}/?$#';
$site['admin']['error'] = 'Wrong URI format.';
$site['username']['name'] = 'Site Admin Username';
$site['username']['val'] = isset($_POST['site_username'])&&!empty($_POST['site_username'])&&trim($_POST['site_username'])!=''? strtolower(trim($_POST['site_username'])) : '';
$site['username']['error'] = 'Username must be combination of letters and digits only. 5-20 chars long.';
$site['username']['regex'] = '#^[a-z0-9]{5,20}$#';
$site['password']['name'] = 'Site Admin Password';
$site['password']['val'] = isset($_POST['site_password'])&&!empty($_POST['site_password'])&&trim($_POST['site_password'])!=''? trim($_POST['site_password']) : '';
$site['password']['error'] = 'Password must be 3-15 chars. And some special chars are not allowed.'; 
$site['password']['regex'] = '#^[a-zA-Z0-9\_\-\/\?\!]{3,15}$#';
foreach($site as &$siteField){
	if($siteField['val']=='' || !preg_match($siteField['regex'],$siteField['val'])){
		$sitePassed = false;
		$siteField['status'] = false;
	} else
		$siteField['status'] = true;
}

// TImezone
$timezones = new \com\won\util\Timezone();
$timezones = $timezones->getTImeZones();
$timezone = isset($_POST['timezone'])?$_POST['timezone']:'US/Eastern';

if($checkPassed&&$dbPassed&&$dbhandlerPassed&&$sitePassed){

$phpass = new \com\won\auth\PHPass();

$bits['site_url'] = $site['url']['val'];
$bits['content_url'] = $site['url']['val'] . 'website/';
$bits['admin_url'] = $site['url']['val'].$site['admin']['val'];
$bits['admin_content_url'] = $site['url']['val'] . 'website_admin/';
$bits['admin_uri'] = $site['admin']['val'];
$bits['module_url'] = $site['url']['val'] . 'system/module/';
$bits['package_url'] = $site['url']['val'] . 'system/com.won/';
$bits['library_url'] = $site['url']['val'] . 'system/library/';
$bits['upload_url'] = $site['url']['val'] . 'website/uploads/'; 

$bits['site_dir'] = SITE_DIR;
$bits['content_dir'] = SITE_DIR . 'website/';
$bits['admin_dir'] = SITE_DIR . 'website_admin/';
$bits['module_dir'] = SITE_DIR . 'system/module/';
$bits['package_dir'] = SITE_DIR . 'system/com.won/';
$bits['library_dir'] = SITE_DIR . 'system/library/';
$bits['upload_dir'] = SITE_DIR . 'website/uploads/';

$bits['timezone'] = $timezone;
$bits['db_host'] = $db['hostname'];
$bits['db_user'] = $db['username'];
$bits['db_pass'] = $db['password'];
$bits['db_db'] = $db['database'];
$bits['charset'] = CHARSET;
$bits['collate'] = COLLATE;
$bits['admin_user'] = $site['username']['val'];
$bits['admin_pass'] = $site['password']['val'];
$bits['dbhandler_uri'] = $dbhandler['uri']['val'];
$bits['dbhandler_uri_trim'] = rtrim($dbhandler['uri']['val'],'/');
$bits['dbhandler_pass'] = $phpass->getHash($dbhandler['password']['val']);
$bits['base'] = str_replace($_SERVER['HTTP_HOST'],'',str_replace(PROTOCOL,'',SITE_URL));

// Write HTACCESS
$htaccessTplFile=fopen(PARTS_DIR. 'htaccess.tpl', 'r');
$htaccessTpl=fread($htaccessTplFile, filesize(PARTS_DIR.'htaccess.tpl'));
fclose($htaccessTplFile);
foreach($bits as $bit=>$val)
	$htaccessTpl=str_replace('{$'.$bit.'}', $val, $htaccessTpl);
$htaccess = fopen(SITE_DIR . '.htaccess', 'w');
fwrite($htaccess, $htaccessTpl);
fclose($htaccess);

// Write Config
$configTplFile=fopen(PARTS_DIR. 'config.tpl', 'r');
$configTpl=fread($configTplFile, filesize(PARTS_DIR.'config.tpl'));
fclose($configTplFile);
foreach($bits as $bit=>$val)
	$configTpl=str_replace('{$'.$bit.'}', $val, $configTpl);
$config = fopen(SITE_DIR . 'config.php', 'w');
fwrite($config, $configTpl);
fclose($config);

// Write DB Handler Config
$dbhTplFile=fopen(PARTS_DIR. 'dbhandlerconfig.tpl', 'r');
$dbhTpl=fread($dbhTplFile, filesize(PARTS_DIR. 'dbhandlerconfig.tpl'));
fclose($dbhTplFile);
foreach($bits as $bit=>$val)
	$dbhTpl=str_replace('{$'.$bit.'}', $val, $dbhTpl);
$dbh = fopen(SITE_DIR . '_db_handler/config.php', 'w');
fwrite($dbh, $dbhTpl);
fclose($dbh);


// Add Admin Data to Database

// Initialize
foreach(glob(SITE_DIR . 'system/engine/*.php') as $engine)
	require_once $engine;
// Load DataTypes, Must load in sequential order for extensions
require_once SITE_DIR .'system/engine/datatype/Table.php';
require_once SITE_DIR .'system/engine/datatype/DataType.php';
require_once SITE_DIR .'system/engine/datatype/Int.php';
require_once SITE_DIR .'system/engine/datatype/Text.php';
require_once SITE_DIR .'system/engine/datatype/Time.php';
require_once SITE_DIR .'system/engine/datatype/Bool.php';
require_once SITE_DIR .'system/engine/datatype/Pkey.php';

$reg=new \app\engine\Registry();
require_once SITE_DIR . 'config.php';
$reg->loader->getClass('server.MagicQuotesFix'); // Run Fixes
$reg->loader->getClass('server.RegisterGlobalsFix');
date_default_timezone_set($reg->config->timezone); // Set Timezone

$reg->user->updateDB();
$reg->user->addGroup('Administrator', false);
$reg->user->addGroup('Member', false);
$reg->user->setDefaultGroup('Member');
$reg->user->addUser($bits['admin_user'], $bits['admin_pass']);
$reg->user->addUserToGroup($bits['admin_user'], 'Administrator');

$reg->url->updateDB();
// Go to Admin
header('Location:'. $bits['admin_url']);
}

else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Webwon Installation</title>
<style>
table{width:400px;}
h1{font:bold 14px/16px arial;}
td{background:#eee;padding:2px 8px;}
.error, .alert{color:#f00;}
input,select{width:100%;}
</style>
</head>

<body>
<form action="<?php echo INSTALL_URL?>" method="post">
	<input type="hidden" name="data" value="data"/>
	<?php 
	require PARTS_DIR . 'requirement.php';
	require PARTS_DIR . 'db.php';
	require PARTS_DIR . 'dbhandler.php';
	require PARTS_DIR . 'site.php';
	?>
    <button<?php $checkPassed?'':' disabled="disabled"';?> type="submit">Submit</button>
</form>
</body>
</html>
<?php } ?>