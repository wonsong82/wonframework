<?php
//namespace com\won\database;
final class com_won_database_MySQL {
	
	public $result;
	public $lastError;
	public $lastQuery;
	private $link;
	
	public function __construct() {}
	
	public function connect($host, $user, $pass, $db){
		$this->link = @mysql_connect($host,$user,$pass);
		$this->selectDb($db);
		if(function_exists('mysql_set_charset')){
			$this->setCharset('utf8');//Set Default Charset to UTF8 until specified later time		
		}
		return $this->link? true:false;
	}
	
	public function query($query, $method="assoc") {
		$this->lastQuery = $query;	
		$this->result = mysql_query($query);
		if(!$this->result){
			$this->lastError = $this->error();
			return false;
		}
		
		if ($this->result) {
			if (is_resource($this->result)) {
				if($method=='assoc'){
					return $this->fetchAssoc();
				}
				elseif($method=='row'){
					return $this->fetchRow();
				}
			}
		}	
		return true;
	}
	
	public function escape($value) {		
		return mysql_real_escape_string(trim($value));
	}	
		
	public function __call($func, $args) {		
		$func = 'mysql_'.strtolower(preg_replace('/([A-Z])/','_$1',$func));
		if (function_exists($func)) {
			if (preg_match('/fetch/',$func)) 
				return $this->fetch($func, $args);
			elseif (preg_match('/num/',$func))
				return $this->num($func, $args);			
			else
				return call_user_func_array($func, $args);
		}
		else {
			trigger_error('function '.$func.' does not exist.');
			exit();
		}
	}
	
	private function fetch($func, $args) {		
		array_unshift($args, $this->result);
		$data = array();
		while ($row = call_user_func_array($func, $args)) {
			$data[] = $row;
		}
		return $data;
	}
	
	private function num($func, $args) {		
		array_unshift($args, $this->result);
		return call_user_func_array($func, $args);
	}
}
?>