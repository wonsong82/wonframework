<?php
class Validate
{
	public $error = array();
		
	public function __construct() 
	{
	}
	
	public function username($val)
	{
		$val = trim(strip_tags($val));
		if (preg_match('#^[a-zA-Z0-9]{5,20}$#', $val))
			return $val;
		else
		{
			$this->error[] = "Username must be combination of alphabets and numbers, 5-20 chars";
			return false;
		}
	}
	
	public function password($val)
	{
		$val = trim(strip_tags($val));
		if (preg_match('#^[a-zA-Z0-9\_\-\/\?\!\-\_]{3,}$#', $val))
			return $val;
		else
		{
			$this->error[] = "Password must contain no special characters, at least 3 chars long";
			return false;
		}
	}
	
	
	/**
	 * @name name($name)
	 * @desc Validate Person's Name. English Only.
	 * @param String $name : Name to be validated 
	 * @return true or false. The class will contain $error if false.
	 */
	public function name($val)
	{
		$val = trim(strip_tags($val));				
		if (preg_match('#^[a-zA-Z][a-zA-Z0-9 .]+$#', $val))
			return $val;
		else
		{
			$this->error[] = 'Invalid person name';
			return false;
		}
		
	}
	
	
	public function phone($val)
	{
		$val = trim(strip_tags($val));
		if(preg_match('#^[0-9]{10}|[0-9]{3} ?[-. ] ?[0-9]{3} ?[-. ] ?[0-9]{4}|\([0-9]{3}\) ?[0-9]{3} ?[-. ] ?[0-9]{4}$#', $val))
			return true;
		else 
		{
			$this->error[] = 'Invalid phone number';
			return false;
		}
		
	}
	
	public function areacode($val)
	{
		$val = trim(strip_tags($val));		
		return preg_match('#^[0-9]{3}$#', $val)? $val : false;
	}
	
	public function phone_first($val)
	{
		$val = trim(strip_tags($val));		
		return preg_match('#^[0-9]{3}$#', $val)? $val : false;
	}
	
	public function phone_last($val)
	{
		$val = trim(strip_tags($val));		
		return preg_match('#^[0-9]{4}$#', $val)? $val : false;
	}
	
	public function street($val)
	{
		$val = trim(strip_tags($val));
		return preg_match('#^[0-9]+.*[a-zA-Z.]+$#', $val)? $val : false;
	}
	
	public function apt($val)
	{
		$val = trim(strip_tags($val));
		return $val!='' ? $val : false;
	}
	
	public function city($val)
	{
		$val = trim(strip_tags($val));
		return preg_match('#^[a-zA-Z -]+$#', $val)? $val : false;
	}
	
	public function zip($val)
	{
		$val = trim(strip_tags($val));
		return preg_match('#^[0-9]{5}|[0-9]{5}-[0-9]{4}$', $val)? $val : false;
	}
	
	public function country($val)
	{
		$val = trim(strip_tags($val));
		return preg_match('#^[a-zA-Z ]+$#', $val)? $val : false;
	}
	
	public function email($val)
	{
		$val = trim(strip_tags($val));
		if (preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#', $val))
			return $val;
		else
		{
			$this->error[] = 'Invalid email address';
			return false;
		}
	}
	
	public function website($val)
	{
		$val = trim(strip_tags($val));
		return preg_match('#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i', $val)? $val : false;
	}
	
	public function test($val) {
		$test = $val;
	}
}
?>