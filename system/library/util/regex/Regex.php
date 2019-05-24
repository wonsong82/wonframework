<?php
/**
	Validator for default pre-defined values
**/
class Regex {
	
	private $email='#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#';
	private $website='#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i';
	
	public function __get($key){
		return $this->$key;
	}
	
	public function __set($key,$val){
		trigger_error('You cannot set any properties for class Regex');
	}
}
?>