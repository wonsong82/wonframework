<?php
abstract class Controller {
	
	protected $reg;
	protected $extensions = array();
	
	public $errorMsg;
	public $errorCode;
	public $model;	
	
	public function __construct($reg) {
		
		$this->reg = $reg;
		
		$module = lcfirst(str_replace('Controller','',get_class($this)));
		$this->model = $this->loader->loadModel($module);		
				
		$this->extensions = $this->loader->loadExtensions($module, $this->model);
		
		if (method_exists($this, 'init'))				
			$this->init();
													
	}
	
	protected function setError($errorCode) {
		
		$this->errorCode = $errorCode;
		$this->errorMsg = $this->model->getError($errorCode);
	}
	
		
	// Display Help of Class Properties & Methods List
	public function help() {
		
		$classes = array($this);
		foreach ($this->extensions as $ext) {
			$classes[] = $ext['controller'];
		}
		
		$this->lib->import('doc.classDoc');
		$module = $this->lib->classDoc->classInfo($classes);
		echo '<pre>'.$module.'</pre>';
		
		
	}	
	
	
	public function __get($class) {
		
		return $this->reg->$class;
	}
	
	public function __call($method, $args) {
		
		foreach ($this->extensions as $ext) {
			if (method_exists($ext['controller'], $method)) {
				return call_user_func_array(array($ext['controller'], $method), $args);				
			}
		}
		
		trigger_error('Call to undefined method '. $method);
	}
	
	public function printr($array) {
		echo '<pre>';print_r($array);echo '</pre>';
	}
}
?>