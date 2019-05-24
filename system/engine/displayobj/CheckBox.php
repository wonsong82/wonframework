<?php
namespace app\engine\displayobj;
class CheckBox extends DisplayObject{
	
	public $value = false; // @override the default
	
	// @override
	// Set its Value :type = boolean
	public function linkData($bool){
		$this->value=(bool)(int)$bool;			
	}
	
	// @override
	//
	public function render(){
		$args['checked'] = $this->value? ' checked="checked"':'';
		parent::render($args);
	}
	
}
?>