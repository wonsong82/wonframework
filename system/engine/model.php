<?php
// Name : Model
// Desc : Model

// namespace app\engine;
class app_engine_Model{
	
	protected $reg;
	protected $moduleName;
	protected $tables;
		
	public function __construct($reg){
		$this->reg = $reg;
		
		$moduleName = str_replace('Model', '', str_replace($this->reg->ns['module'], '', get_class($this)));
		$moduleName[0] = strtolower($moduleName[0]);
		$this->moduleName = $moduleName;
		
		$this->tables = array();	
	}
	
	//
	// To Access Engines & Modules
	public function __get($name){
		return $this->reg->$name;
	}	
	
	//
	// Add Table, Initiate Table Class with default values
	public function table($tableName){
		if(!isset($this->tables[$tableName])){
			$tableClass = $this->reg->ns['datatype'] . 'Table';			
			$this->tables[$tableName] = new $tableClass($tableName, $this->reg);
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
		// Look for matches of [table.field] from query
		if(preg_match_all('#\[([a-zA-Z-_]+?)\.([a-zA-Z-_]+?)\]#',$query,$matches)){			
			for($i=0;$i<count($matches[0]);$i++){
				$from = $matches[0][$i]; // [table.field]
				$table = $matches[1][$i]; // table
				$field = $matches[2][$i]; // field
				$field = $field=='id' ? 'id' : $this->field($table.'.'.$field)->name; // field_ko
				$to = "`{$table}`.`{$field}`";
				$query = str_replace($from, $to, $query);												
			}
			
		// If none found or only found [table], or [field]	
		}
		$query = preg_replace('#\[|\]#','`',$query);	
		
		$result = $this->db->query($query);
		return $result; // false on errors, true on no return queries, array on select
	}
	
	
	
	
}
?>