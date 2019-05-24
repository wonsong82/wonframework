<?php
require_once '../../../config.php';
Won::set(new Images());
Won::set(new Contents());

switch ($_POST['method'])
{	
	case 'add' :
		$id= Won::get('Images')->addImage($_POST['image'], $_POST['width'], $_POST['height'], $_POST['twidth'], $_POST['theight']);
		echo Won::get('Contents')->update_element_value($_POST['element_id'], $id);
		break;	
	
	case 'update_image' :
		echo Won::get('Images')->updateImage($_POST['image_id'],$_POST['width'],$_POST['height'],$_POST['x'],$_POST['y'],$_POST['x2'],$_POST['y2']);
		
		break;
		
	case 'update_thumb' :
		echo Won::get('Images')->updateThumb($_POST['image_id'],$_POST['width'],$_POST['height'],$_POST['x'],$_POST['y'],$_POST['x2'],$_POST['y2']);
	
		break;
	
	default :
		break;
}
?>
