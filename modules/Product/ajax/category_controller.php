<?php
require_once '../../../config.php';

Won::set(new Product());
Won::set(new Images());

switch ($_POST['method'])
{
	case 'add' :
		Won::get('Product')->add_category($_POST['title']);	
		break;
	
	case 'remove' :
		Won::get('Product')->remove_category($_POST['id']);
		break;
	
	case 'update_sort' : 
		Won::get('Product')->update_sort_category($_POST['ids']);
		break;
	
	
	case 'update_title' :		
		echo Won::get('Product')->update_title_category($_POST['id'], $_POST['value']);
		break;
		
	case 'update_available' : 
		Won::get('Product')->update_available_category($_POST['id'], $_POST['value']);
		break;
		
		
	case 'update_description' :		
		Won::get('Product')->update_description_category($_POST['id'], $_POST['value']);
		break;
		
	case 'add_image' :
		$size = getimagesize($_POST['image']);
		$w = $_POST['width'];
		$h = intval(($size[1]*$w)/$size[0]);
				
		$id= Won::get('Images')->addImage($_POST['image'], $w, $h, $_POST['twidth'], $_POST['theight']);
		echo Won::get('Product')->add_image_category($_POST['item_id'], $id);
		
		break;		
	
	
	case 'update_image' :
		echo Won::get('Images')->updateImage($_POST['image_id'],$_POST['width'],$_POST['height'],$_POST['x'],$_POST['y'],$_POST['x2'],$_POST['y2']);
		
		break;
		
	case 'update_image_sort' :
		echo Won::get('Product')->update_image_sort_category($_POST['item_id'], $_POST['ids']);
	
		break;
	
	case 'remove_image' :
		echo Won::get('Images')->removeImage($_POST['image_id']);
		echo Won::get('Product')->remove_image_category($_POST['item_id'], $_POST['image_id']);
		break;
	
	
	
	default :
		break;
}
?>
