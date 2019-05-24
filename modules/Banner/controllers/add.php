<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$banner = new Banner();

$f = new File();

$file = $_FILES['bannerimage'];

if (!is_dir(CONTENT_DIR . '/banners'))
	mkdir(CONTENT_DIR . '/banners');

if (!$f->upload($file, CONTENT_DIR . '/banners'))
{
	echo $f->error;
	exit();
}

$imgpath = '/banners/' . $f->name;

$banner->add($imgpath, '', '', '');


?>
