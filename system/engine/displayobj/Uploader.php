<?php
// namespace app\engine\displayobj;
class app_engine_displayobj_Uploader extends app_engine_displayobj_DisplayObject{
	
	public $type = '*.*'; // File is default, you can set image,
	public $imgWidth = 800; // If type is image
	public $imgHeight = 600; // if type is image
	public $thumbWidth = 100;
	public $thumbHeight = 100;
	
	
	// @override
	//
	public function render(){
		
		$args['type'] = $this->type;
		$args['imgWidth'] = $this->imgWidth;
		$args['imgHeight'] = $this->imgHeight;
		$args['thumbWidth'] = $this->thumbWidth;
		$args['thumbHeight'] = $this->thumbHeight;
		
		parent::render($args);
	}
	
}
?>