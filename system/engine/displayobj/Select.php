<?php
namespace app\engine\displayobj;
class Select extends DisplayObject{
	
	public $value = array();	
	public $selected = 0;
		
	// @override
	// Set Value
	public function linkData(array $options, $selected=0){
		$this->value = $options;
		$this->selected = $selected;				
	}
	
	// @override
	// 
	public function render(){
		$options=array();
		if(count($this->value)){
			for($i=0;$i<count($this->value);$i++){
				$key = key($this->value);
				$val = $this->value[$key];
				next($this->value);
				$options[] = $i==$this->selected?
					'<option selected="selected" value="'.$val.'">'.$key.'</option>':
					'<option value="'.$val.'">'.$key.'</option>';				
			}
		}
		$args['options'] = implode("\n", $options);
		
		parent::render($args);
	}
	
}
?>