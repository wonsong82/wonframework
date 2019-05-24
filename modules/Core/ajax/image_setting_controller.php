<?php
require_once '../../../config.php';

Won::set(new Settings());

switch ($_POST['method'])
{
	case 'update_img_size' :
		$w = trim($_POST['imgw']);
		$h = trim($_POST['imgh']);
		if (is_numeric($w) && is_numeric($h)) {
			echo Won::get('Settings')->setSetting('Images', 'imgSize', array('width'=>$w,'height'=>$h));
		} 
		else {
			echo 'value must be numeric, and both fields must be set.';
		}
		break;
	
	case 'update_thumb_size' :
		$w = trim($_POST['thw']);
		$h = trim($_POST['thh']);
		if (is_numeric($w) && is_numeric($h)) {
			echo Won::get('Settings')->setSetting('Images', 'thumbSize', array('width'=>$w, 'height'=>$h));
		} 
		else {
			echo 'value must be numeric, and both fields must be set.';
		}
		break;	
	
	
	default :
		break;
}
?>