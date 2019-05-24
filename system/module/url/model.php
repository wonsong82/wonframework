<?php
// Name : Url Model
// Desc : Url Model

// namespace app\module;
final class app_module_UrlModel extends app_engine_Model{
		
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$f = $this->table('url')->field('uri', 'text');
		$f->regex = '#^[a-zA-Z0-9\/\-\_$]*$#';
		$f->key = 'unique';
		
		$f = $this->table('url')->field('template', 'text');
		$f->regex = '#[a-zA-Z0-9.-_]+\.php$#';
		$f->key = 'index';	
		
	}
}
?>