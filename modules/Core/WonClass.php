<?php
class WonClass
{
	public $error;
	
	public function __construct()
	{
		if (!$this->config_loaded())
		{
			throw new Exception('Config is not loaded.');
			exit();
		}			
		
			$this->init();		
	}
	
	
	
	private function config_loaded()
	{
		return 
			property_exists('Won', 'classes') &&
			false !== Won::get('Config') &&
			Won::get('Config')->loaded ?
			
			true : false;		
	}
	
	public function __get($var) {
		Won::set(new $var());
		return Won::get($var);
	}
}
?>