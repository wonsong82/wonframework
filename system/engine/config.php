<?php
final class Config {
	
	private $data = array();
	
	public function __get($config) {
		
		return isset($this->data[$config])? $this->data[$config] : null;
	}
	
	public function __set($config, $value) {
		
		$this->data[$config] = $value;
	}
}
?>