<?php
// Library resides on root/library folder, 
final class Lib {
	
	private $reg;
	private $data = array();
	
	public function __construct($registry) {
		$this->reg = $registry;
	}
	
	// Cannot set any properties
	public function __set($key,$val) {
		trigger_error('Manually setting a variable to Lib class is prohibited.');
		exit();
	}
	
	// Library selector
	public function __get($library) {
		if (isset($this->data[$library]))
			return $this->data[$library];
		else {
			trigger_error("{$library} library is not available.");
			exit();
		}
	}
	
	// Load the library for one time use
	public function load($library, $args=null){
		$path = str_replace('.', '/', $library); //change dot notation to directory
		$class = ucwords(basename($path)); //bit of this library(folder name lowercase)
		$path = $this->reg->config->libraryDir.$path.'/'.$class.'.php';
		if (!file_exists($path)) {
			trigger_error ("failed to locate the file for the library : {$library}");
			exit();
		}
		require_once $path;
		return new $class($args);
	}
	
		
	// Dot notation of the library path. (Load library for general use)
	public function import($library) {
		$path = str_replace('.', '/', $library); //change dot notation to directory
		$bit = basename($path); //bit of this library(folder name lowercase)
		
		//add to data if not set yet
		if (!isset($this->data[$bit])) {
			$class = ucwords($bit);
			$path = $this->reg->config->libraryDir.$path.'/'.$class.'.php';
			if (!class_exists($class)) {
				if (!file_exists($path)) {
					trigger_error ("failed to locate the file for the library : {$library}");
					exit();
				}			
				require_once $path;
			}
			$this->data[$bit] = new $class();			
		}
	}
}
?>