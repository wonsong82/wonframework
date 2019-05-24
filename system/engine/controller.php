<?php
// namespace app\engine;
class app_engine_Controller{

	protected $reg;
	public $model; // Model of this Module
	public $name; // Module Name
	
	public $langText;
	
	//
	// Constructor
	public function __construct($reg){
		$this->reg = $reg;				
		
		$moduleName = str_replace('Controller', '', str_replace($this->reg->ns['module'], '', get_class($this)));
		$moduleName[0] = strtolower($moduleName[0]);
		$this->name = $moduleName;
		
		//Load the Model
		$modelFile = $this->config->moduleDir. $moduleName . '/model.php';
		$modelClass = $moduleName . 'Model'; // moduleModel
		$modelClass[0] = strtoupper($modelClass[0]); // ModuleModel
		$modelClass = $this->reg->ns['module'] . $modelClass; // app_module_ModuleModel
		
		if(!class_exists($modelClass) && file_exists($modelFile)){
			require_once $modelFile;
			$this->model = new $modelClass($reg);
		}
		if($this->config->isAdmin){
			$this->updateDB();	
		}
		//Load the Language Text File
		$this->loadTexts();		
	}
	
	//
	// Returns the text from lang file by current language
	public function getText($key){
		return isset($this->langText[$key])? $this->langText[$key]:$key;
	}
	
	//
	// Load the current language file into this object
	public function loadTexts(){
		$curLangFile = $this->lang->lang . '.php';
		$defaultLangFile = $this->lang->defaultLang . '.php';
		$curLangFile = $this->config->moduleDir . $this->name . '/lang/' . $curLangFile;
		$defaultLangFile = $this->config->moduleDir . $this->name . '/lang/' . $defaultLangFile;
		$langFile = null;		
		if(file_exists($curLangFile)){
			$langFile = $curLangFile;
		} 
		elseif(file_exists($defaultLangFile)){
			$langFile = $defaultLangFile;
		}
		$this->langText = array();
		if (!is_null($langFile) && filesize($langFile)!=0){
			$f=fopen($langFile,'r');
			$lang=fread($f, filesize($langFile));
			fclose($f);
			$langsImploded=explode("\n",$lang);
			foreach($langsImploded as $row){
				if(trim($row)!='' && strstr(trim($row),'==>')){
					$arr=explode('==>',trim($row));
					$this->langText[trim($arr[0])] = trim($arr[1]);
				}
			}
		}
	}
	
	//
	// Returns the last error occured
	public function lastError(){
		return isset($this->error)? $this->getText($this->error):'';
		
	}
	
	public function updateDB(){
		$this->model->updateDB();
	}
	
	//
	// Get objects from Registry
	public function __get($name){
		return $this->reg->$name;
	}
	
	// Update Sort Order of the table with the ids provided,
	// incrementing order startin from $start
	// The field must have 'order' field
	public function updateOrder($table, $ids, $start=0){
		$ids = explode(',',$ids);
		$to = count($ids) + $start;
		$success=true;
		for($i=$start; $i<$to; $i++){
			$result = $this->model->query("
				UPDATE [{$table}]
				SET [order] = {$i}
				WHERE [{$table}.id] = {$ids[$i]} 
			");
			
			if($result==false)
				$success=false;
		}
		if(!$success){
			$this->error = "Update Order Error";
			return false;
		}
		return true;
	}
	
	public function nextOrder($table){
		$table=$this->db->escape($table);
		$result = $this->model->query("
			SELECT MAX([order]) AS [max] FROM [{$table}]
		");
		if(false===$result){
			$this->error = 'Invalid Table Name';
			return false;
		}
		return count($result)? ((int)$result[0]['max'])+1: 0;
	}
	
	public function select($field, $id){
		// values
		$id = (int)$this->db->escape($id);
		
		// Update
		$table = preg_replace('#\.[a-zA-Z-_0-9]+$#', '', $field);
		$result= $this->model->query("
			SELECT [{$field}] AS [f]
			FROM [{$table}]
			WHERE [{$table}.id] = {$id}
		");
		if(count($result)>0)
			return $result[0]['f'];	
		if($result===false){
			return false;
		}
	}
	
	// Update Individual Fields
	public function update($field, $id, $val){
		// Validate
		if(!$this->model->field($field)->validate($val)){
			$this->error = 'Error While Validating Field';
			return false;
		}
		
		// values
		$id = (int)$this->db->escape($id);
		if(is_bool($val)) $val = (int)$val;
		$val = $this->db->escape($val);		
		
		// Update
		$table = preg_replace('#\.[a-zA-Z-_]+$#', '', $field);
		$result = $this->model->query("
			UPDATE [{$table}]
			SET [{$field}] = '{$val}'
			WHERE [{$table}.id] = {$id}
		");
		
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		return true;	
	}
	
	public function reverseArray($array){
		$newArr = array();
		for($i=count($array)-1;$i>=0;$i--){
			$newArr[] = $array[$i];
		}
		$array = $newArr;
		return $array;
	}
}
?>