<?php
// Name : Registry
// Desc : Registry of the App.
namespace app\engine;
final class Registry {
	
	private $data = array();
	
	//
	// Constructor
	//
	public function __construct(){
		$this->data['loader'] = new Loader($this);
	}
	
	//
	// Register Engines and Modules for quick access and singletron.
	//
	public function __get($name){
		if (isset($this->data[$name])){
			return $this->data[$name];
		}
		elseif (false !== $instance = $this->loader->getEngine($name)){
			$this->data[$name] = $instance;
			return $instance;
		}
		elseif (false !== $instance = $this->loader->getModule($name)){
			$this->data[$name] = $instance;
			return $instance;
		}
		else {
			trigger_error(__CLASS__ . ': Cannot get "' . $name . '"');
			return false;			
		}
	}
}

?>