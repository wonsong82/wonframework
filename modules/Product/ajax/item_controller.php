<?php
require_once '../../../config.php';

Won::set(new Product());
Won::set(new Images());
Won::set(new Videos());

switch ($_POST['method'])
{
	case 'add_item_by_title' :
		Won::get('Product')->add_item_by_title($_POST['title'], $_POST['cat']);	
		break;		
		
	case 'remove' :
		Won::get('Product')->remove_item($_POST['id']);
		break;
		
	case 'update_sort' : 
		Won::get('Product')->update_sort_item($_POST['ids']);
		break;	
	
	case 'update_title' : 
		echo Won::get('Product')->update_title_item($_POST['id'], $_POST['value']);
		break;
		
	case 'update_subtitle' : 
		Won::get('Product')->update_subtitle_item($_POST['id'], $_POST['value']);
		break;		
	
	case 'update_description' : 
		Won::get('Product')->update_description_item($_POST['id'], $_POST['value']);
		break;
	
	case 'update_itemid' : 
		Won::get('Product')->update_itemid_item($_POST['id'], $_POST['value']);
		break;
	
	case 'update_price' : 
		Won::get('Product')->update_price_item($_POST['id'], $_POST['value']);
		break;
		
	case 'update_weight' : 
		Won::get('Product')->update_weight_item($_POST['id'], $_POST['value']);
		break;	
		
	case 'update_available' : 
		Won::get('Product')->update_available_item($_POST['id'], $_POST['value']);
		break;
	
	case 'update_stock' : 
		Won::get('Product')->update_stock_item($_POST['id'], $_POST['value']);
		break;
	
	case 'add_image' :
		$size = getimagesize($_POST['image']);
		$w = $_POST['width'];
		$h = intval(($size[1]*$w)/$size[0]);
				
		$id= Won::get('Images')->addImage($_POST['image'], $w, $h, $_POST['twidth'], $_POST['theight']);
		echo Won::get('Product')->add_image_item($_POST['item_id'], $id);
		
		break;
	
	case 'update_image' :
		echo Won::get('Images')->updateImage($_POST['image_id'],$_POST['width'],$_POST['height'],$_POST['x'],$_POST['y'],$_POST['x2'],$_POST['y2']);
		
		break;
	
	case 'update_image_sort' :
		echo Won::get('Product')->update_image_sort_item($_POST['item_id'], $_POST['ids']);
	
		break;
	
	case 'remove_image' :
		echo Won::get('Images')->removeImage($_POST['image_id']);
		echo Won::get('Product')->remove_image_item($_POST['item_id'], $_POST['image_id']);
		break;
	
		
	case 'add_video' :
		$id= Won::get('Videos')->addVideo($_POST['title'],$_POST['video'],$_POST['thumb']);
		echo Won::get('Product')->add_video_item($_POST['item_id'], $id);
		break;
		
	case 'update_video_sort' :
		echo Won::get('Product')->update_video_sort_item($_POST['item_id'], $_POST['ids']);
	
		break;
		
	case 'remove_video' :
		echo Won::get('Videos')->removeVideo($_POST['video_id']);
		echo Won::get('Product')->remove_video_item($_POST['item_id'], $_POST['video_id']);
		break;
	
	
	default :
		break;
}
?>
