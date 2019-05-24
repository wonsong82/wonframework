<?php
// namespace app\engine\datatype;
class app_engine_datatype_Int extends app_engine_datatype_Datatype{
	
	protected $max;
	protected $unsigned;
	protected $sizes = array( // IN BYTES
		'TINYINT' => 1,
		'SMALLINT' => 2,
		'MEDIUMINT' => 3,
		'INT' => 4,
		'BIGINT' => 5
	);
	
	public function __construct($name, $reg){
		parent::__construct($name, $reg);
		$this->sqlType = 'INT';
		$this->regex = '#^[0-9]+$#';
		$this->max = 2147483647;
		$this->length = 10;
		$this->unsigned = false;
	}
	
	public function set($key, $val){
		if($key=='max'){
			$this->setMax($val);
			return;
		}
		parent::set($key, $val);		
	}
	
	public function get($key){
		if($key=='max')
			return $this->max;
		if($key=='unsigned')
			return $this->unsigned;
		return parent::get($key);		
	}
	
	protected function setMax($int){
		$int = abs($int);
		// Find right type
		foreach ($this->sizes as $name=>$bytes){
			$max = pow(2, $bytes*8);
			$max = $this->unsigned? $max : $max/2;
			if($int<$max){
				$this->max = $int;
				$this->sqlType = $name;
				$this->length = strlen((string)$int);
				return;
			}
		}
	}
}
?>