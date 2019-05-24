<?php
namespace app\engine\displayobj;
class ImageEditor extends DisplayObject{
	
	protected $img; // Image Information
	protected $imgData; // Image Metadata Information
	
	
	// @override
	//  $imgID is the id of the image
	public function linkData($imgID){
		$this->img = $this->image->getImage($imgID);
		$this->imgData = $this->image->getImageData($this->img['id']);		
	}
	
	public function render(){
		$args['img'] = $this->img;
		$args['imgData'] = $this->imgData;
		parent::render($args);
	}
	
}
?>