<?php
final class Mysql {
	
	public $result;	
	private $link;
	
	public function connect($host, $user, $pass, $db) {
		$this->link = mysql_connect($host,$user,$pass) or die('Could not connect to DB');
		$this->selectDb($db);
		$this->setCharset('utf8');
	}
	
	public function query($query) {		
		$this->result = mysql_query($query) or die($this->error());
		if ($this->result) {
			if (is_resource($this->result)) {
				return $this->fetchAssoc();
			}
		}	
		return $this;
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