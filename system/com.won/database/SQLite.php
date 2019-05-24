<?php
// namespace com\won\database;
final class com_won_database_SQLite{
	
	public $result;
	private $link;
	
	public function __construct($db){
		if(is_file($db)) chmod($db, 0777);		
		$this->link = new SQLite3($db);		
	}
	
	
	public function query($query){
		$this->result=$this->link->query($query);
		
		if($this->result && $this->result->numColumns() != 0){
			$data=array();
			while($row=$this->result->fetchArray(SQLITE3_ASSOC))
			{	
				$data[]=$row;
			}
			return $data;			
		}
		return $this;		
	}
	
	// Escape a string. Database must be connected first
	public function escape($value){
		return $this->link->escapeString($value);
	}
	
	public function showTables(){
		return $this->link->query("SELECT name FROM sqlite_master WHERE type='table'");		
	}
	
	public function __call($func, $args){
		if(method_exists($this->link, $func)){
			return call_user_func_array(array($this->link, $func), $args);
		} 
		else {
			trigger_error('function "'.$func.'" does not exist in '.__CLASS__);
			exit();
		}		
	}
}
?>