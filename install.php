<?php

// Load Engines
foreach (glob(dirname(__FILE__).'/system/engine/*.php') as $startEngine) {
	require_once $startEngine;
}

// Start the Registry
$registry = new Registry();

// Load Config
require_once dirname(__FILE__).'/config.php';

enableModule('url');
enableModule('user');
enableModule('contact');
enableExtension('contact.contactSocials');
enableExtension('contact.contactHours');
/*

include $registry->config->moduleDir.'url/model.php';
$urlModel = new UrlModel($registry);
$urlModel->setDB();

include $registry->config->moduleDir.'url/extension/urlExtended/model.php';
$urlExtModel = new UrlExtendedModel($registry, $urlModel);
$urlExtModel->setDB();

include $registry->config->moduleDir.'user/model.php';
$userModel = new UserModel($registry);
$userModel->setDB();
*/
function enableModule($module){
	global $registry;
	$moduleDir=$registry->config->moduleDir.$module;
	if(is_dir($moduleDir.'_disabled'))
		rename($moduleDir.'_disabled',$moduleDir);
	include_once $moduleDir.'/model.php';
	$class=ucwords($module).'Model';
	$model=new $class($registry);
	$model->setDB();
}
function disableModule($module){
	global $registry;
	$moduleDir=$registry->config->moduleDir.$module;
	if(is_dir($moduleDir))
		rename($moduleDir,$moduleDir.'_disabled');
}
function enableExtension($extension){
	global $registry;
	$str=explode('.',$extension);
	$mod=$str[0];
	$modDir=$registry->config->moduleDir.$mod;
	include_once $modDir.'/model.php';
	$modClass=ucwords($mod).'Model';
	$modModel=new $modClass($registry);
	$ext=$str[1];
	$extDir=$registry->config->moduleDir.$mod.'/extension/'.$ext;
	if(is_dir($extDir.'_disabled'))
		rename($extDir.'_disabled',$extDir);
	include_once $extDir.'/model.php';
	$extClass=ucwords($ext).'Model';
	$extModel=new $extClass($registry,$modModel);
	$extModel->setDB();
}
?>