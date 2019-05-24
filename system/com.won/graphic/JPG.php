<?php
namespace com\won\graphic;
class JPG{
	
	private $imgObj; //Image Resource
	private $path; // Image File Path
	
	//
	// Load the image from file	
	public function __construct($filePath){
		$this->path = $filePath;
		$this->imgObj = imagecreatefromjpeg($filePath);
	}
	
	//
	// Crop or Resize the image, destroying previous one.
	public function resample($destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH){
		$src = $this->imgObj;
		$dest = imagecreatetruecolor($destW, $destH);
		$resample = imagecopyresampled($dest, $src, $destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH);
		imagedestroy($this->imgObj);
		$this->imgObj = $dest;
		return $dest && $resample? true:false;			
	}
	
	//
	// Save the image obj to file
	// $quality = 0~100 , default 85
	public function save($filePath, $quality=85){
		return imagejpeg($this->imgObj, $filePath, $quality);
	}
	
	//
	// Destroy the image to free the resource
	public function destroy(){
		imagedestroy($this->imgObj);
	}	
}
?>