<?php
// namespace app\engine\displayobj;
class app_engine_displayobj_Row extends app_engine_displayobj_DisplayObject{
	public $cols;
	
	// @override
	// Construct
	public function __construct($id,$reg){
		parent::__construct($id,$reg);
		$this->cols=array();
	}
		
	//
	// Add Column
	public function addCol($key,$val){
		$this->cols[$key] = $val;
	}
	
	// @override
	//
	public function render(){
		$args['cols'] = $this->cols;
		parent::render($args);
	}
}
?>