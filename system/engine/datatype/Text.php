<?php
// namespace app\engine\datatype;
class app_engine_datatype_Text extends app_engine_datatype_DataType{
	
	public function __construct($name, $reg){
		parent::__construct($name, $reg);
		$this->sqlType = 'VARCHAR';
		$this->length = 255;
		$this->regex = false;	
	}
	
	public function set($key, $val){
		if($key=='length') {
			$this->length = (int)$val;
			if($this->length > 255)
				$this->sqlType = 'TEXT';
		}
		parent::set($key, $val);				
	}
	
	public function get($key){
		if($key=='sqlType'){
			if($this->sqlType == 'VARCHAR')
				return $this->sqlType . '(' . $this->length . ') NOT NULL DEFAULT "'.$this->reg->db->escape($this->default).'"';
			else
				return $this->sqlType . ' NOT NULL DEFAULT "'. $this->reg->db->escape($this->default).'"';
		}
		return parent::get($key);		
	}	
}
?>