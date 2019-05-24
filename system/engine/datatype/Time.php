<?php
// namespace app\engine\datatype;
class app_engine_datatype_Time extends app_engine_datatype_DataType{
	
	public function __construct($name, $reg){
		parent::__construct($name, $reg);
		$this->sqlType = 'DATETIME';
		$this->regex = '#^[0-9]{4}-[0-9]{2}-[0-9]{2}( [0-9]{2}:[0-9]{2}:[0-9]{2})?$#';	
	}
}
?>