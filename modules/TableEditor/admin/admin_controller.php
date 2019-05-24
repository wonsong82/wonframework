<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

// load class
$table_editor = new TableEditor();

switch ($_POST['method'])
{
	case 'update' :			
		$table_editor->update($_POST['table'], $_POST['id'], $_POST['key'], $_POST['val']);
		break;
		
	case 'add' :
		$table = $_POST['table'];
		$param = $_POST;
		unset($param['table']);
		unset($param['method']);	
		$table_editor->add($table, $param);
		break;
		
	case 'remove' :
		$table_editor->delete($_POST['table'], $_POST['id']);
		break;
	
	default :
		break;
}
?>
