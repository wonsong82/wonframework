<?php
// Name : Uname Model
// Desc : Unique Name Table 

// namespace app\module;
final class app_module_UnameModel extends app_engine_Model{
	
	//
	// DB Structure
	public function __construct($reg){
		parent::__construct($reg);
		$f = $this->table('uname')->field('name', 'text');
		$f->regex = '#[a-zA-Z0-9\/\-\_$]+$#';
		$f->key = 'unique';
	}
	
		
	
}
?>
