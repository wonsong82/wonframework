<?php
// Name : DataType Interface
// Desc : Predefined Datatypes for MySQL and Others

// namespace app\engine\datatype;
class app_engine_datatype_DataType{
	
	// Common Variables
	protected $title; // Title of the Field
	protected $description; // Description of the Field
	protected $name; // Name of the Field
	protected $type; // Type of the Field
	protected $sqlType; // MYSQL Type
	protected $length; // Length of the characters for this field
	protected $regex; // Regex for Input
	protected $key; // Defined Keys for MYSQL Data
	protected $default = null;
	protected $reg;
	
	public function __construct($name, $reg){
		$this->name = $name;
		$this->reg = $reg;	
		$this->type = str_replace( $this->reg->ns['datatype'], '', strtolower(get_class($this)));		
	}
	
	public function __set($key, $val){
		$this->set($key, $val);
	}
	
	public function __get($key){
		return $this->get($key);
	}
	
	public function validate($val){
		if(is_bool($val)) $val=(int)$val;
		if(false===$this->regex) return true;
		return preg_match($this->regex, $val);
	}
	
	//
	// Default: return null so the values won be changed;
	// Other Langs: change name and return this
	public function lang($langCode){
		if(!$this->reg->lang->isDefault && 
			$this->reg->lang->lang == $langCode){		
			$this->name .= '_'.$langCode;
			return $this;
		}
		else{
			return $this;
		}
	}
	
	
	
	protected function set($key, $val){
		if($key=='title'){
			$this->title = $val;
			return;
		}
		if($key=='description'){
			$this->description = $val;
		}
		if($key=='name'){
			$this->name = $val;
			return;
		}
		if($key=='regex'){
			$this->regex = $val;
			return;
		}
		if($key=='key'){
			$this->key = $val;
			return;
		}
		if($key=='length'){
			$this->length = (int)$val;
			return;
		}
		if($key=='default'){
			$this->default = $val;
		}
	}
	
	protected function get($key){
		if($key=='title')
			return $this->title;
		if($key=='description')
			return $this->description;
		if($key=='name')
			return $this->name;
		if($key=='sqlType'){
			if($this->default!==null){
				$d = is_bool($this->default) ? (int)$this->default : $this->default;
				$default = is_numeric($d)? $d : "'{$d}'";
				return $this->sqlType. ' NOT NULL DEFAULT '. $default;
			} else {
				return $this->sqlType;
			}
		}
		if($key=='regex')
			return $this->regex;
		if($key=='length')
			return $this->length;
		if($key=='type')
			return $this->type;
		if($key=='key')
			return $this->getMySQLKey($this->key);
	}
	
	private function getMySQLKey($key){
		$key = strtolower($key);
		if($key=='primary') return 'PRIMARY KEY';
		if($key=='unique') return 'UNIQUE';
		if($key=='index') return 'INDEX';
	}
}
?>