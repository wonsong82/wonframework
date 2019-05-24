<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$table = $_POST['table'];
$id = $_POST['id'];

// load class
require_once '../TableEditor.php';
$table_editor = new TableEditor();

$table_editor->delete($table, $id);
?>
