<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

Won::set(new Banner());

$f = new File();
$file = $_FILES['bannerimage'];

if (!is_dir(Won::get('Config')->content_dir . '/banners'))
	mkdir(Won::get('Config')->content_dir . '/banners');

if (!$f->upload($file, Won::get('Config')->content_dir . '/banners'))
{
	echo $f->error;			
}

else
{
	$imgpath = '/banners/' . $f->name;
	Won::get('Banner')->add($imgpath, '', '', '');
	
	header('location:'. $_POST['url']);
}
?>