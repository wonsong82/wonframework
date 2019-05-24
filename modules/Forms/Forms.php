<?php
//ModuleNavigationSortOrder=9
class Forms extends WonClass{
	
	private $table;
	
	// Init table structure
	public function init(){
		$this->table=$this->DB->prefix.'forms';
		$this->DB->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}`(
				`id` SERIAL NOT NULL,
				`form_name` VARCHAR(255) NOT NULL DEFAULT '',
				`values` TEXT NOT NULL DEFAULT '',
				`date` DATETIME NOT NULL,
				PRIMARY KEY(`id`)
			) ENGINE=INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		");
	}
	

/* GET CONTROLLERS */

	public function getForms(){
		$forms=$this->DB->query("
			SELECT DISTINCT `form_name` FROM `{$this->table}`
		");
		$data=array();
		if ($forms->num_rows){
			while($form=$forms->fetch_assoc()){
				$data[]=$form['form_name'];
			}
		}
		return $data;
	}
		
	public function getRecords($formName){		
		$records=$this->DB->query("
			SELECT * FROM `{$this->table}`
			WHERE `form_name`='{$formName}'
			ORDER BY `date` DESC
		");
		$data=array();
		if ($records->num_rows){
			while($record=$records->fetch_assoc()){
				$record['values']=unserialize($record['values']);
				$data[]=$record;
			}
		}
		return $data;
	}
	
	public function getRecord($id){
		$id=(int)$id;
		$record=$this->DB->query("
			SELECT * FROM `{$this->table}`
			WHERE `id`={$id}
		");
		$data=array();
		if ($record->num_rows){
			$record=$record->fetch_assoc();
			$record['values']=unserialize($record['values']);
			$data=$record;	
		}
		return $data;
	}


/* DB CONTROLLERS */ 
	
	//Add Record, values must be serialized values
	public function addRecord($formName,$values) {
		$name=$this->DB->sql->real_escape_string($formName);
		$values=$this->DB->sql->real_escape_string($values);
		$time=date('Y-m-d H:i:s');
		$this->DB->query("
			INSERT INTO `{$this->table}`
			SET `form_name`='{$name}',
				`values`='{$values}',
				`date`='{$time}'
		");
	}
	
	//Remove Record
	public function removeRecord($id) {
		$id=(int)$id;
		$this->DB->query("
			DELETE FROM `{$this->table}`
			WHERE `id`={$id}
		");
	}
	
	//Update
	public function updateRecord($id,$values) {
		$id=(int)$id;
		$values=$this->DB->sql->real_escape_string($values);
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `values`='{$values}'
			WHERE `id`={$id}
		");
		
	}


}
?>