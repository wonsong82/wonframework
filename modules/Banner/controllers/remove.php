<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$banner = new Banner();

$id = $_POST['id'];

$banner->remove($id);
?>
