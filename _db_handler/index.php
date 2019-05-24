<?php
require_once dirname(__FILE__).'/../initialize.php';

// Redirect Correct the URI
if(!preg_match('#\/$#',$reg->req->server['REQUEST_URI'])) {
	header('location: http://'.$reg->req->server['HTTP_HOST'].$reg->req->server['REQUEST_URI'].'/');
}

// Defines
$reg->config->dbDir=$reg->config->siteDir.'_db_handler/';
$reg->config->dbTemplateDir=$reg->config->dbDir.'templates/';
$reg->config->dbBackupDir=$reg->config->dbDir.'databases/';

// Add Config
require_once $reg->config->dbDir . 'config.php';
$reg->config->db=$reg->config->site . DBURI;

// Authorize Security First
$hash = $reg->loader->getClass('auth.PHPass');
$auth = $reg->loader->getClass('auth.SimpleAuth', 'webwon_db_handler');
$auth->addUser('admin', DBPASS);
$isAuth = $auth->auth();

// If Unauthorized, Display A Login Form
if(!$isAuth || (isset($reg->req->post['action']) && $reg->req->post['action']=='logout')){
	$auth->logout();
	include $reg->config->dbTemplateDir.'form.inc.php';
	exit();
}

// Page Includes
include $reg->config->dbTemplateDir.'menu.inc.php';
if(!isset($reg->req->post['action'])){
	;
}
elseif($reg->req->post['action']=='backup'){
	include $reg->config->dbTemplateDir.'backup.inc.php';
}
elseif($reg->req->post['action']=='restore'){
	include $reg->config->dbTemplateDir.'restore.inc.php';
}
elseif($reg->req->post['action']=='password'){
	include $reg->config->dbTemplateDir.'password.inc.php';
}
?>