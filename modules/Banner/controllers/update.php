<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';
	
$banner = new Banner();

$id = $_POST['id'];
$key = $_POST['key'];
$value = trim($_POST['value']);


if ($value == '')
{
	echo '<span class="error"></span>';
	exit();
}

$banner->update($id, $key, $value);
?>
