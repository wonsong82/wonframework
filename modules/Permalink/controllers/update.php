<?php

if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';
	
$permalink = new Permalink();

$id = $_POST['id'];
$key = $_POST['key'];
$value = trim($_POST['value']);



if ($value == '')
{
	echo '<span class="error"></span>';
	exit();
}

$permalink->update($id, $key, $value);
?>


