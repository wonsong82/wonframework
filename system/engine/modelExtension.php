<?php
abstract class ModelExtension extends Model {
	
	protected $model;
	
	public function __construct($reg, $model) {
		
		$this->reg = $reg;
		$this->model = $model;
		
		$this->init();
	}
	
	public function __call($method, $args) {
		
		if (method_exists($this->model, $method)) {
			return call_user_func_array(array($this->model, $method), $args);
		}
		
		trigger_error('Call to undefined method ' . $method);
	}
	
	public function __get($key) {
		
		return $this->model->$key;
	}
	
	public function __set($key, $val) {
		
		return $this->model->$key = $val;
	}
	
	public function validate($input, $ref) {
		
		return $this->model->validate($input, $ref);
	}
	
	public function setField($ref, $fieldName, $type, $regex, $index=null) {
		
		return $this->model->setField($ref, $fieldName, $type, $regex, $index);
	}
	
	public function setError($key, $error) {
		
		return $this->model->setError($key, $error);
	}
	
	public function getError($key) {
		
		return $this->model->getError($key);
	}
	
	public function getFields($tableRef) {
		
		return $this->model->getFields($tableRef);
	}
	
	public function help() {
		
		return $this->model->help();
	}
	
	public function setDB() {
		
		return $this->model->setDB();
	}
}
?>