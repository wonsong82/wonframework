<?php
// Name : Image
// Desc : Represents of a single image file
namespace com\won\graphic;
class Image{
	
	public $imgObj;
	public $mimeType;
	public $ratio;
	public $width;
	public $height;
	
	
	// Allowed Mime Types
	private $mimeTypes = array('gif','png','jpg');
	
		
	//
	// Load Image from File Path
	public function load($filePath){
		// If file isnt accessbile, return false
		if(!file_exists($filePath)){
			trigger_error(__CLASS__ . ': invalid file path.');
			return false;
		}
		// Get Mime Type
		$fileInfo = pathinfo($filePath);
		$extension = strtolower($fileInfo['extension']);
		if($extension=='jpeg') $extension = 'jpg';
		if(in_array($extension, $this->mimeTypes)){
			$this->mimeType = $extension;
		}
		else {
			trigger_error(__CLASS__ . ': unsupported image mime type.');
			return false;
		}
		// Import and Instantiate
		$classFile = dirname(__FILE__) . '/' . strtoupper($this->mimeType) . '.php';
		$class = 'com\\won\\graphic\\'.strtoupper($this->mimeType);
		require_once($classFile);
		$this->imgObj = new $class($filePath);
		// Get Properties
		$s = getimagesize($filePath);
		$this->width = (int)$s[0];
		$this->height = (int)$s[1];
		$this->ratio = (int)$s[0]/(int)$s[1];
		
		return true;
	}
	
	public function save($filePath, $param=null){
		if(!is_dir(dirname($filePath)))
			mkdir(dirname($filePath));
		
		$result = $param==null? $this->imgObj->save($filePath): $this->imgObj->save($filePath, $param);
		
		if(!$result){
			trigger_error(__CLASS__.': could not save the image to '.$filePath);
			return false;
		}
	}
	
	public function destroy(){
		$this->imgObj->destroy();
		unset($this->imgObj);
		return true;
	}
	
	//
	// Resize Image
	public function resize($width=null,$height=null){
		
		// check Parameters
		if(!$width && !$height){
			trigger_error(__CLASS__ . ': Resize needs either width or height, or both.');
			return false;
		}
		
		// Get Width & Height
		if(!$width){
			$h = (int)$height;
			$w = (int)($h * $this->ratio);
		}
		elseif(!$height){
			$w = (int)$width;
			$h = (int)($w / $this->ratio);		
		}
		else{
			$w = (int)$width;
			$h = (int)$height;		
		}
		
		// Resize
		if(!$this->imgObj->resample(0,0,0,0, $w, $h, $this->width, $this->height)){
			trigger_error(__CLASS__ . ': Error while resizing.');
			return false;
		}
		$this->width = $w;
		$this->height = $h;
		$this->ratio = $w / $h;
		
		return true;			
	}
	
	//
	// Crop the Image
	public function crop($x1, $y1, $x2, $y2){
		
		$x1 = (int)$x1;
		$y1 = (int)$y1;
		$x2 = (int)$x2;
		$y2 = (int)$y2;
		$w = $x2 - $x1;
		$h = $y2 - $y1;
		
		// Crop
		if(!$this->imgObj->resample(0,0, $x1, $y1, $w, $h, $w, $h)){
			trigger_error(__CLASS__ . ': Error while Cropping.');
			return false;
		}
		$this->width = $w;
		$this->height = $h;
		$this->ratio = $w / $h;		
		
		return true;
	}
	
	
}

?>