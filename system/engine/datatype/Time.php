<?php
namespace app\engine\datatype;
class Time extends DataType{
	
	public function __construct($name, $registry){
		parent::__construct($name, $registry);
		$this->sqlType = 'DATETIME';
		$this->regex = '#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$';	
	}
}
?>