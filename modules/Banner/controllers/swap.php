<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';
	
$banner = new Banner();

$id1 = $_POST['id1'];
$id2 = $_POST['id2'];

$banner->swap($id1, $id2);
?>
