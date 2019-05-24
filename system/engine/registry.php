<?php
// Name : Registry
// Desc : Registry of the App.

// namespace app\engine;
final class app_engine_Registry {
	
	private $data;
	public $ns;
	
	//
	// Constructor
	public function __construct($ns=array()){
		$this->data = array();
		$this->ns = $ns;
		$loaderClass = $ns['engine'] . 'Loader';
		$this->data['loader'] = new $loaderClass($this);		
	}
	
	//
	// Register Engines and Modules for quick access and singletron.
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