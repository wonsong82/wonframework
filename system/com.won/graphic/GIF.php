<?php
namespace com\won\graphic;
class GIF{
	
	private $imgObj; //Image Resource
	private $path; // Image File Path
	
	//
	// Load the image from file	
	public function __construct($filePath){
		$this->path = $filePath;
		$this->imgObj = imagecreatefromgif($filePath);
	}
	
	//
	// Crop or Resize the image, destroying previous one.
	public function resample($destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH){
		$src = $this->imgObj;
		$dest = imagecreatetruecolor($destW, $destH);
		
		//Get Info for Alpha
		$info = $this->info($this->path);
		if($info['version']=='89a'&&$info['flag']==1){
			imagealphablending($dest,false);
			imagesavealpha($dest,true);
			$transparent = imagecolorallocatealpha($dest, $info['trans_red'],$info['trans_green'], $info['trans_blue'], 127);
			imagecolortransparent($dest, $transparent);
		}
		
		$resample = imagecopyresampled($dest, $src, $destX, $destY, $srcX, $srcY, $destW, $destH, $srcW, $srcH);
				
		imagedestroy($this->imgObj);
		$this->imgObj = $dest;
		
		return $dest && $resample? true:false;			
	}
	
	//
	// Save the image obj to file
	public function save($filePath){
		return imagegif($this->imgObj, $filePath);
	}
	
	//
	// Destroy the image to free the resource
	public function destroy(){
		imagedestroy($this->imgObj);
	}	
	
	private function info($filePath){
		$fp = fopen($filePath,'rb');
		$result =fread($fp,13);
		$info = array(
			"signature" => substr ($result, 0, 3), 
		  "version" => substr ($result, 3, 3), 
		  "width" => ord (substr ($result, 6, 1)) + ord (substr ($result, 7, 1)) * 256, 
		  "height" => ord (substr ($result, 8, 1)) + ord(substr ($result, 9, 1)) * 256, 
		  "flag" => ord (substr ($result, 10, 1)) >> 7, 
		  "trans_red" => ord (substr ($result, ord (substr ($result, 11)) * 3,1)), 
		  "trans_green" => ord (substr ($result, ord (substr($result, 11)) * 3 + 1, 1)), 
		  "trans_blue" => ord (substr ($result, ord (substr($result, 11)) * 3 + 2, 1)) 
		);
		fclose($fp);
		unset($fp);
		return $info;
	}
}
?>