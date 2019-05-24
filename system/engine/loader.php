<?php
final class Loader {
	
	private $reg;
	
	public function __construct($registry) {
		
		$this->reg = $registry;
	}
	
	
	public function loadModel($moduleName) {
		
		if (!class_exists(ucfirst($moduleName.'Model')) && 
			file_exists($this->reg->config->moduleDir.$moduleName.'/model.php')) {
			require $this->reg->config->moduleDir.$moduleName.'/model.php';
			$model = ucfirst($moduleName.'Model');
			return new $model($this->reg);
		}			
	}
	
	public function loadExtensions($moduleName, $modelInstance) {
		
		$exts = array();
		foreach (glob($this->reg->config->moduleDir.$moduleName.'/extension/*', GLOB_ONLYDIR) as $extDir) {
			if (!preg_match('#_disabled$#', basename($extDir))) {
				require $extDir.'/model.php';
				require $extDir.'/controller.php';
				$model = ucfirst(basename($extDir)).'Model';
				$controller = ucfirst(basename($extDir)).'Controller';
				$modelExt = new $model($this->reg, $modelInstance);
				$controllerExt = new $controller($this->reg, $modelExt);
				
				$exts[] = array(
					'controller'=> $controllerExt ,
					'model' => $modelExt
				);
			}
		}
		return $exts;
	}
	
}
?>