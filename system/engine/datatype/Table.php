<?php
// namespace app\engine\datatype;
final class app_engine_datatype_Table{
	
	public $name;
	public $charset;
	public $collate;
	public $fields;
	
	private $reg;
	
	public function __construct($tableName, $reg){
		$this->name = $tableName;		
		$this->fields = array();
		$this->reg = $reg;
	}
	
	public function field($fieldName, $type){
		if(!isset($this->fields[$fieldName])){
			$type[0] = strtoupper($type[0]);
			$typeClass = $this->reg->ns['datatype'].$type;
			$this->fields[$fieldName] = new $typeClass($fieldName, $this->reg);			
		}
		return $this->fields[$fieldName];
	}
	
}
?>