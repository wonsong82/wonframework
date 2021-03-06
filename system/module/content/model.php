<?php
// Name : Content Model
// Desc : Contents From SQL
// namespace app\module;
final class app_module_ContentModel extends app_engine_Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$field = $this->table('content')->field('name', 'text');
		$field->regex = '#^.+$#';
		$field->key = 'unique';
		
		$field = $this->table('content')->field('content', 'text');
		$field->length = 1000;
		
		$field = $field->lang('ko');
		$field = $field->lang('cn');
		$field = $field->lang('jp');
	}
}
?>