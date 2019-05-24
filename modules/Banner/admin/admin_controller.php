<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$banner = new Banner();;

switch ($_POST['method'])
{
	case 'update' :		
		if (!$banner->update($_POST['id'], $_POST['key'], $_POST['value']))
			echo $banner->error;
		break;
		
	case 'add' :
		$f = new File();
		$file = $_FILES['bannerimage'];
		
		if (!is_dir(CONTENT_DIR . '/banners'))
			mkdir(CONTENT_DIR . '/banners');
	
		if (!$f->upload($file, CONTENT_DIR . '/banners'))
		{
			echo $f->error;			
		}
		
		else
		{
			$imgpath = '/banners/' . $f->name;
			$banner->add($imgpath, '', '', '');
		}	
	
		break;
		
	case 'remove' :
		$banner->remove($_POST['id']);
		break;
	
	case 'swap' :
		$banner->swap($_POST['id1'], $_POST['id2']);
		break;
	
	default :
		break;
}
?>
