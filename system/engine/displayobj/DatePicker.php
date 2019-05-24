<?php
// namespace app\engine\displayobj;
class app_engine_displayobj_DatePicker extends app_engine_displayobj_DisplayObject{
		
	// @override
	// Constructor
	public function __construct($id,$reg){
		parent::__construct($id,$reg);
		$this->text = '';
		$this->value='';
	}
	
	// @override
	public function render(){
		parent::render();
	}
	
	// @override
	// Set Value
	public function linkData($string){
		$this->value=$string;
	}
	
}
?>