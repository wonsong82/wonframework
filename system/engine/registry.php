<?php
final class Registry {
	
	private $data = array();
	
	public function __get($class) {
		
		// if already initiated, return (initiate once and no more)
		if (isset($this->data[$class])) {
			return $this->data[$class];
		}
		
		else {
			
			$className = ucwords($class);
			
			// if start engine,			
			if (class_exists($className)) {				
				$this->data[$class] = new $className($this);
				return $this->data[$class];
			}		
			
			// if module
			elseif (file_exists($this->config->moduleDir.$class.'/controller.php')) {
				require_once $this->config->moduleDir.$class.'/controller.php';
				
				$className .= 'Controller';
				$this->data[$class] = new $className($this);
				return $this->data[$class];
			}
			
			// error
			else {
				trigger_error ('cannot load '.$class);
				exit();
			}
		}
	}		
}

?>