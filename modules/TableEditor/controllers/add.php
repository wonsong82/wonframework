<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

// load class
$table_editor = new TableEditor();

$table = $_POST['table'];
// get posts and make these as param except $_POST['table']
$param = $_POST;
unset($param['table']);

$table_editor->add($table, $param);
?>