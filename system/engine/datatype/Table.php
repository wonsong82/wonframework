<?php
namespace app\engine\datatype;
final class Table{
	
	public $name;
	public $charset;
	public $collate;
	public $fields;
	
	private $registry;
	
	public function __construct($tableName, $registry){
		$this->name = $tableName;		
		$this->fields = array();
		$this->registry = $registry;
	}
	
	public function field($fieldName, $type){
		if(!isset($this->fields[$fieldName])){
			$typeClass = '\\app\\engine\\datatype\\'.ucwords($type);
			$this->fields[$fieldName] = new $typeClass($fieldName, $this->registry);			
		}
		return $this->fields[$fieldName];
	}
	
}
?>