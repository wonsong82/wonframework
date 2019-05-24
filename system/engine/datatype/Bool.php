<?php
// namespace app\engine\datatype;
class app_engine_datatype_Bool extends app_engine_datatype_Int{
	
	public function __construct($name, $reg){
		parent::__construct($name, $reg);
		$this->sqlType = 'TINYINT';
		$this->length = 1;
		$this->max = 1;
		$this->unsigned = true;
	}
}
?>