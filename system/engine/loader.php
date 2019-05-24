<?php
// Name : Loader
// Desc : Loads Internal & External Assets into the App
// namespace app\engine;

final class app_engine_Loader{

	private $reg;
	private $css = array();
	private $js = array();

	//
	// Entry, register Registry
	public function __construct($reg){
		$this->reg = $reg;
	}
	
	//
	// The engines should be preincluded, 
	// returns new instance of the engine, otherwise false 
	public function getEngine($engineName){
		$engineClass = $this->reg->ns['engine'] . $this->uf($engineName);
		return class_exists($engineClass)?
			new $engineClass($this->reg) : false;	
	}

	//
	// Returns a new instance of the Module Controller,
	// Otherwise return false
	public function getModule($moduleName){
		$moduleControllerFile = $this->reg->config->moduleDir . $moduleName . '/controller.php';
		$moduleControllerClass = $this->reg->ns['module'] . $this->uf($moduleName) . 'Controller';
		if (file_exists($moduleControllerFile)) {
			require_once $moduleControllerFile;
			return class_exists($moduleControllerClass)?
				new $moduleControllerClass($this->reg) : false;
		}
		else
			return false;
	}
	
	// 
	// Import and return a new instance of External Class within com.won Package
	// Pass args after packagePath if theres any
	// Multiple instance of this is allowed
	public function getClass($packagePath, $args=null){
		$classFile = $this->reg->config->packageDir . str_replace('.','/',$packagePath) . '.php';
		$classClass = $this->reg->ns['package'] . str_replace('.', $this->reg->ns['SEPARATOR'], $packagePath);
		if(!class_exists($classClass)) 
			require $classFile;
		$refClass = $this->reg->ns['base'] . 'ReflectionClass';
		$refClass = new $refClass($classClass);
		$arg = func_get_args();
		$arg = array_splice($arg, 1);
		return $refClass->newInstanceArgs($arg);
	}

	//
	// Add the Library 
	// Add the Library composed with JS, CSS, and its Requirements
	// Load the Library If its not loaded yet.
	public function getLib($path){
		$req=array();
		$css=array();
		$js=array();
		$data='';
		$info=$this->reg->config->libraryDir . str_replace('.','/',$path).'/info.php';
		$path=$this->reg->config->library . str_replace('.','/',$path);
		
		if(!file_exists($info)){
			trigger_error("cannot locate info file from ".$path);
			return false;
		}
		require $info;
		foreach($req as $r)
			$data.=$this->getLib($r);
		
		foreach($css as $id=>$href){
			if(!in_array($id,$this->css)){
				$this->css[]=$id;
				$data.='<link rel="stylesheet" type="text/css" href="'.$path.'/'.$href.'"/>'."\n";
			}
		}
				
		foreach($js as $id=>$src){
			if(!in_array($id,$this->js)){
				$this->js[]=$id;
				$data.='<script type="text/javascript" src="'.$path.'/'.$src.'"></script>'."\n";		
			}
		}
		
		return $data;
	}	
	

	private function lf($string){
		$string[0] = strtolower($string[0]);
		return $string;
	}
	

	private function uf($string){
		$string[0] = strtoupper($string[0]);
		return $string;
	}

	

}
?>