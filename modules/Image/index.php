<?php
set_time_limit(0);
include "class.WonJPG.php";
$wonjpg = new WonJPG();

$dir = 'imgs/';
$imgSize = 1000;

$dirHandle = opendir($dir);

while (false !== ($file = readdir($dirHandle))) 
{
	if ( preg_match('/.jpg$/i', $file) )
	{
				
		if (!is_dir($dir.'resized'))
			mkdir($dir.'resized');
		
		$size = getimagesize($dir . $file);
		
		if ($size[0] >= $size[1]) {
			$width = $imgSize;
			$height = round($size[1] * $imgSize / $size[0]);		
		}
		
		else {
			$width = round($size[0] * $imgSize / $size[1]);
			$height = $imgSize;
		}
		
		$wonjpg->resize($dir.$file, $dir.'resized/'.$file,$width,$height);
		
	}
}


?>