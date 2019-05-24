<?php
namespace app\engine\displayobj;
class TextField extends DisplayObject{
		
	public $password = false; // Is Password (bool)
	public $static = false; // Is Statuc TextField (label)
	
	// @override
	// Constructor
	public function __construct($id,$reg){
		parent::__construct($id,$reg);
		$this->value='';
	}
	
	// @override
	public function render(){
		$keys['type'] = $this->password? 'password':'text';
		$keys['static'] = $this->static;
		
		parent::render($keys);
	}
	
	// @override
	// Set Value
	public function linkData($string){
		$this->value=$string;
	}
	
}
?>