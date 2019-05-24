<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

Won::set(new Banner());

switch ($_POST['method'])
{
	case 'update' :		
		if (!Won::get('Banner')->update($_POST['id'], $_POST['key'], $_POST['value']))
			echo Won::get('Banner')->error;
		break;
	
		
	case 'remove' :
		echo Won::get('Banner')->remove($_POST['id']);
		break;
	
	case 'swap' :
		echo Won::get('Banner')->swap($_POST['id1'], $_POST['id2']);
		break;
	
	default :
		break;
}
?>
