<?php
// Version : 4.0
// Load the Initializer
require_once dirname(__FILE__).'/initialize.php';

$reg->template->render();

$reg->user;
//include $reg->config->moduleDir.'user/model.php';
//$m = new \app\module\UserModel($reg);

//$reg->url->updateDB();

//$reg->template->render();

/*
include $reg->config->moduleDir.'url/model.php';
$m = new \app\module\UrlModel($reg);
$m->updateDB();
*/

/*
$b=$m->query("INSERT INTO [url] SET [url.uri]='aaa', [url.template]='aaa.php'");

$b=$m->query("
	SELECT [url.id] AS [id], [url.uri] AS [uri], [url.template] AS [template] FROM [url]
");

var_dump($b);
*/
//$model = new \app\module\LangsModel($reg);
//$model->updateDB();
//$model->select();
//$model->updateDatabase();
//$model->select(1,2,3);


// Output the Http Response
//$registry->template->output();


?>