<?php
// Name : Config
// Desc : Config Instance 

// namespace app\engine;
final class app_engine_Config {
	
	private $data = array();
	
	public function __construct(){}
	
	public function __get($config){		
		return isset($this->data[$config])? $this->data[$config] : null;
	}
	
	public function __set($config, $value){		
		$this->data[$config] = $value;
	}
}
?>