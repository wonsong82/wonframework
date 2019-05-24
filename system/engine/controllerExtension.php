<?php
abstract class ControllerExtension extends Controller {
	
	public function __construct($reg, $model) {
		
		$this->reg = $reg;
		$this->model = $model;
		
		if (method_exists($this, 'init'))
			$this->init();		
	}
	
	
	
	
}
?>