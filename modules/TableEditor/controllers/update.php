<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

// load class
$table_editor = new TableEditor();

// get posts
$table = $_POST['table'];
$id = $_POST['id'];
$key = $_POST['key'];
$val = $_POST['val'];

$table_editor->update($table, $id, $key, $val);
?>