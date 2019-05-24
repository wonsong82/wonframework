<?php
// Name : Gallery Model
// Desc : Photo Gallery

// namespace app\module;
final class app_module_GalleryModel extends app_engine_Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$f = $this->table('gallery')->field('title', 'text');
		$f = $this->table('gallery')->field('desc', 'text');
		$f = $this->table('gallery')->field('imgid', 'pkey');
		$f = $this->table('gallery')->field('is_banner', 'bool');
		$f->default = false;				
	}
}
?>