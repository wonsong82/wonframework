<?php
require_once '../../../config.php';

Won::set(new User());

switch ($_POST['method'])
{
	case 'add' :
		Won::get('User')->add_group($_POST['name']);	
		break;
	
	case 'remove' :
		Won::get('User')->remove_group($_POST['id']);
		break;
	
	case 'update_sort' : 
		Won::get('User')->update_group_sort($_POST['ids']);
		break;
	
	
	case 'update_name' :		
		Won::get('User')->update_group_name($_POST['id'], $_POST['value']);
		break;
		
		
	
	default :
		break;
}
?>
