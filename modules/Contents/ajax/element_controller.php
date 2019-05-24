<?php
require_once '../../../config.php';
Won::set(new Contents());

switch ($_POST['method'])
{	
	case 'add' :
		echo Won::get('Contents')->add_element($_POST['type'], $_POST['page_id']);			
		break;
	
	case 'remove' :
		echo Won::get('Contents')->remove_element($_POST['id']);
		break;
	
	case 'update_sort':
		echo Won::get('Contents')->update_element_sort($_POST['ids']);
		break;
		
	case 'update_parent':
		echo Won::get('Contents')->update_element_parent($_POST['element_id'], $_POST['parent_id']);
		break;
		
	case 'update_title':
		echo Won::get('Contents')->update_element_title($_POST['element_id'], $_POST['title']);
		break;
	
	case 'update_value':
	
		echo Won::get('Contents')->update_element_value($_POST['element_id'], $_POST['value']);
		break;
		
	case 'update_link':
		echo Won::get('Contents')->update_link($_POST['element_id'], $_POST['text'], $_POST['href']);
		break;
	
	default :
		break;
}
?>
