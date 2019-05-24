<?php
abstract class Model {
	
	// Registry
	protected $reg;
	protected $moduleName;
	protected $extensions;
	protected $errors = array();
	
	public $table = array(); 
		
	// Magic Methods			
	public function __construct($reg) {
		
		$this->reg = $reg;
		$this->moduleName = strtolower(str_replace('Model','',get_class($this)));
		$this->init(); //Initialize Model Data Structure			
	}
	
	public function __get($class) {
		
		return $this->reg->$class;
	}
	
	
	// Validate. $ref in table.fieldBit format
	public function validate($patternRef, $value) {
		
		$ref = explode('.',$patternRef);
		$table = $ref[0];
		$field = $ref[1];
		
		return preg_match($this->table[$table]['fields'][$field]['regex'], $value);
	}
	
		
	// Set fields and save it to the $thi->table[]	
	public function setField($ref, $fieldName, $type, $regex=null, $index=null) {
			
		$ref = explode('.',$ref); // 0 : tableName, 1 : fieldName
		$table = $ref[0];
		$field = $ref[1];
		
		//Table Setup
		$this->table[$table]['name'] = $this->db->prefix . $this->moduleName . '_' . $table;	
		$this->table[$table]['fields'][$field]['name'] = $fieldName;
		$this->table[$table]['fields'][$field]['type'] = $type;
		$this->table[$table]['fields'][$field]['regex'] = $regex;
		$this->table[$table]['fields'][$field]['index'] = $index;
		
	}
	
	public function setError($key, $error) {
		
		$this->errors[$key] = $error;
	}
	
	public function getError($key) {
		
		if (!isset($this->errors[$key])) {
			trigger_error($key . ' is not a valid ErrorCode.');
		}
		$error = $this->errors[$key];
		preg_match_all('#{\$.+?}#',$error, $vars);
		$args = func_get_args();
		array_shift($args);
		if (count($vars = $vars[0]) != count($args)) {
			trigger_error("Error {$key} needs ".count($vars)." arguments.");
			exit();
		}
		foreach ($args as $arg) {
			$error = preg_replace('#{\$.*?}#', $arg, $error, 1);			
		}		
		return $error;
	}
	
	// Return comma deliminated string 
	protected function getFields($tableRef) {
		
		$fields = '`id`,';
		foreach ($this->table[$tableRef]['fields'] as $fKey=>$fVal) {
			$fields .= '`'.$fVal['name'].'` AS `'.$fKey.'`,';
		}
		
		return rtrim($fields, ',');
	}
	
	protected function getFieldName($tableRef) {
		$ref = explode('.',$tableRef);
		$table = $ref[0];
		$field = $ref[1];
		return $this->table[$table]['fields'][$field]['name'];
	}
	
	protected function getTableName($tableRef) {
		return $this->table[$tableRef]['name'];
	}
	
	public function add($table) {
		$table=$this->getTableName($table);
		$this->db->query("
			INSERT INTO `{$table}` (`id`) VALUES (null)
		");
	}
	
	public function update($tableRef, $id, $val) {
		$ref = explode('.',$tableRef);
		$table = $ref[0];
		$field = $ref[1];
		$tableName = $this->table[$table]['name'];
		$fieldName = $this->table[$table]['fields'][$field]['name'];
		$id=(int)$id;
		$val=$this->db->escape($val);
		$this->db->query("
			UPDATE `{$tableName}`
			SET `{$fieldName}`='{$val}'
			WHERE `id`={$id};
		");		
	}
	
	public function remove($table, $id) {
		$id=(int)$id;
		$table=$this->getTableName($table);
		$this->db->query("
			DELETE FROM `{$table}`
			WHERE `id`={$id}
		");
	}
	
	
	public function help() {
		
		$this->lib->import('doc.classDoc');		
		echo '<pre>'.$this->lib->classDoc->classInfo($this).'</pre>';
	}
	
	
	
	public function setDB() {
		
		foreach ($this->table as $table) {
			
			$tableName = $table['name'];
			
			// Create Table if not exist			
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `{$tableName}` (
					`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
				) ENGINE = INNODB CHARSET `{$this->config->charset}` COLLATE `{$this->config->collate}`
			");
			
			// Modify Columns Structures
			$tableInfo = $this->db->query("
				DESCRIBE `{$tableName}`
			");
						
			foreach ($table['fields'] as $field) {
				
				$found=0;
				foreach ($tableInfo as $existingField) {
					if ($existingField['Field'] == $field['name'])
						$found=1;
				}
				if (!$found) {
					$this->db->query("
						ALTER TABLE `{$tableName}` 
						ADD COLUMN `{$field['name']}` {$field['type']}  
					");
					if ($field['index']) {
						$this->db->query("
							ALTER TABLE `{$tableName}`
							ADD {$field['index']} (`{$field['name']}`)
						");	
					}
				}								
			}			
		}			
	}
}
?>