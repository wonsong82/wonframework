<?php
// Name : Model
// Desc : Model
namespace app\engine;
class Model{
	
	protected $registry;
	protected $moduleName;
	protected $tables;
		
	public function __construct($registry){
		$this->registry = $registry;
		$this->moduleName = strtolower(str_replace('Model','',get_class($this)));
		$this->tables = array();	
	}
	
	//
	// To Access Engines & Modules
	public function __get($name){
		return $this->registry->$name;
	}	
	
	//
	// Add Table, Initiate Table Class with default values
	public function table($tableName){
		if(!isset($this->tables[$tableName])){
			$this->tables[$tableName] = new \app\engine\datatype\Table($tableName, $this->registry);
			$this->tables[$tableName]->charset = $this->config->charset;
			$this->tables[$tableName]->collate = $this->config->collate;
		}
		return $this->tables[$tableName];		
	}
	
	public function field($path){
		// url.uri
		$path=explode('.',$path);
		
		if(count($path)==2){
			if(isset($this->tables[$path[0]]) && isset($this->tables[$path[0]]->fields[$path[1]]))
			return $this->tables[$path[0]]->fields[$path[1]];
		}
		else return false;
	}
	
	
	//
	// Database Update
	public function updateDB(){
		
		foreach ($this->tables as $table){
			
			// Create Table IF NOT EXISTS
			$this->query("
				CREATE TABLE IF NOT EXISTS `{$table->name}` (
					`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
					`order` BIGINT UNSIGNED DEFAULT 0,
					PRIMARY KEY(`id`), INDEX(`order`)
				) ENGINE = INNODB CHARSET `{$table->charset}` COLLATE `{$table->collate}`
			");
			// Modify the Field
			$tableInfo = $this->query("DESCRIBE `{$table->name}`");	
			foreach ($table->fields as $field){
				$exist=false;
				foreach($tableInfo as $existingField){
					if($existingField['Field']==$field->name)
						$exist=$existingField;
				}
				if($exist){
					$this->query("
						ALTER TABLE `{$table->name}`
						MODIFY COLUMN `{$field->name}` {$field->sqlType}
					");
					if(!empty($exist['Key'])){
						$this->query("
							ALTER TABLE `{$table->name}`
							DROP INDEX `{$field->name}`
						");
					}
				}
				else {
					$this->query("
						ALTER TABLE `{$table->name}`
						ADD COLUMN `{$field->name}` {$field->sqlType}
					");					
				}				
				if($field->key){
					$this->query("
						ALTER TABLE `{$table->name}`
						ADD {$field->key} (`{$field->name}`)
					");
				}			
			}			
		}
	}
	
	//
	// 
	public function query($query){
		if(preg_match_all('#\[([a-zA-Z-_]+?)(\.[a-zA-Z-_]+?)\]#',$query,$matches)){
			$query = preg_replace('#\[|\]#','`',$query);
			for($i=0;$i<count($matches[0]);$i++){
				$replace=$matches[2][$i]=='.id'?'id':$this->field($matches[1][$i].$matches[2][$i])->name;
				$query = str_replace($matches[2][$i], '`.`'.$replace, $query);												
			}
		}
		else {
			$query = preg_replace('#\[|\]#','`',$query);			
		}
		
		$result = $this->db->query($query);
		return $result; // false on errors, true on no return queries, array on select
	}
	
	
	
	
}
/*











*/











?>