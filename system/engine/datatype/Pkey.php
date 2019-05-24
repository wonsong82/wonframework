<?php
namespace app\engine\datatype;
class Pkey extends Int{
		
	public function __construct($name, $registry){
		parent::__construct($name, $registry);
		$this->sqlType = 'BIGINT';
		$this->regex = '#^[0-9]+$#';
		$this->length = 19;
		$this->max = 18446744073709551615;
		$this->unsigned = true;	
	}
}
?>