<?php
require_once '../../../config.php';
Won::set(new Contents());

switch ($_POST['method'])
{	
	case 'add' :
		echo Won::get('Contents')->add_page($_POST['uri']);			
		break;
	
	case 'remove' :
		echo Won::get('Contents')->remove_page($_POST['id']);
		break;

	default :
		break;
}
?>
