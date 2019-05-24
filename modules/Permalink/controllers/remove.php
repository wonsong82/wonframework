<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';
	
$permalink = new Permalink();

$id = $_POST['id'];

$permalink->remove($id);
?>
