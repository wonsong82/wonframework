<?php
/**
	Validator for default pre-defined values
**/
class Validate {
	
	
	
	
	public function email($v) {
		
		return preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#', $v)? true : false;
	}
	
	
	public function website($v) {
		
		return preg_match('#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i', $v)? true : false;
	}
	
	
}
?>