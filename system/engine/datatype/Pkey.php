<?php
// namespace app\engine\datatype;
class app_engine_datatype_Pkey extends app_engine_datatype_Int{
		
	public function __construct($name, $reg){
		parent::__construct($name, $reg);
		$this->sqlType = 'BIGINT';
		$this->regex = '#^[0-9]+$#';
		$this->length = 19;
		$this->max = 18446744073709551615;
		$this->unsigned = true;	
	}
}
?>