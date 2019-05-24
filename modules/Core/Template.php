<?php
class Template extends WonClass {
	
	protected function init() {
		
		
	}
	
	public function __get($var) {
		Won::set(new $var());
		return Won::get($var);
	}
	
	public function printTemplate() {
		require Won::get('Permalink')->template;
	}
	
}
?>