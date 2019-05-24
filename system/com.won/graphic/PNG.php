<?php
// namespace com\won\graphic;
class com_won_graphic_PNG{
	
	private $imgObj; //Image Resource
	private $path; // Image File Path
	
	//
	// Load the image from file
	public function __construct($filePath){
		$this->path = $filePath;
		$this->imgObj = imagecreatefrompng($filePath);
		imagealphablending($this->imgObj, false);
		imagesavealpha($this->imgObj, true);
	}
	
	//
	// Crop or Resize the image, destroying previous one.
	public function resample($destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH){
		$src = $this->imgObj;
		$dest = imagecreatetruecolor($destW, $destH);
		imagealphablending($dest, false);
		imagesavealpha($dest, true);
		$transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
		imagefilledrectangle($dest, 0, 0, $destW, $destH, $transparent);
		
		$resample = imagecopyresampled($dest, $src, $destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH);
		
		imagedestroy($this->imgObj);
		$this->imgObj = $dest;
		return $dest && $resample? true:false;			
	}
	
	//
	// Save the image obj to file
	// $compression = 0~9 , default 9
	public function save($filePath, $compression=9){
		return imagepng($this->imgObj, $filePath, $compression);
	}
	
	//
	// Destroy the image to free the resource
	public function destroy(){
		imagedestroy($this->imgObj);
	}	
}
?>