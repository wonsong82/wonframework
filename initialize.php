<?php
// Redirect to Install If the APP isnt properly installed
if (!file_exists(dirname(__FILE__).'/config.php')) {
	header('location:install');
	exit();
}


// Load Engines
foreach (glob(dirname(__FILE__).'/system/engine/*.php') as $engine) {
	require_once $engine;
}

// Namespaces
require_once dirname(__FILE__).'/namespaces.php';


// Load DataTypes, Must load in sequential order for extensions
require_once dirname(__FILE__).'/system/engine/datatype/Table.php';
require_once dirname(__FILE__).'/system/engine/datatype/DataType.php';
require_once dirname(__FILE__).'/system/engine/datatype/Int.php';
require_once dirname(__FILE__).'/system/engine/datatype/Text.php';
require_once dirname(__FILE__).'/system/engine/datatype/Time.php';
require_once dirname(__FILE__).'/system/engine/datatype/Bool.php';
require_once dirname(__FILE__).'/system/engine/datatype/Pkey.php';


// Start the Registry
$reg = new app_engine_Registry($ns);

// Load Config
require_once dirname(__FILE__).'/config.php';

$reg->loader->getClass('server.MagicQuotesFix'); // Run Fixes
$reg->loader->getClass('server.RegisterGlobalsFix');

$reg->lang->langs = array(
		'en' => 'english',
		'ko' => '한글',		
		'cn' => '中國語',
		'jp' => '日本語'
);
$reg->lang->defaultLang = 'en';


// Start the Session
$reg->session->start(); 

// Start
date_default_timezone_set($reg->config->timezone); // Set Timezone
set_time_limit(0);

// URL Structure
$url = $reg->loader->getClass('web.Url');
$url->url = $url->unifyUrl($reg->config->site);

// Check and Set Language From URI
$url->uri = $url->getRealUri($reg->config->site, $url->url); 
$url->first = explode('/',$url->uri);
$url->first = $url->first[0];

$reg->lang->set($url->first);
if(!$reg->lang->isDefault){
	$reg->config->site .= $reg->lang->lang .'/';
	$reg->config->admin = $reg->config->site . $reg->config->adminUri;
}

// Check if its admin page
$reg->config->isAdmin = preg_match('#'.$reg->config->adminUri.'#i', $reg->req->server['REQUEST_URI']);
$reg->config->isAjax = preg_match('#ajax/#', $reg->req->server['REQUEST_URI']);

?>