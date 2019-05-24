<?php
namespace app\engine\datatype;
class Bool extends Int{
	
	public function __construct($name, $registry){
		parent::__construct($name, $registry);
		$this->sqlType = 'TINYINT';
		$this->length = 1;
		$this->max = 1;
		$this->unsigned = true;
	}
}
?>