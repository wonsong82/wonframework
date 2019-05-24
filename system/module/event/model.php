<?php
// Name : Event Model
// Desc : Events

// namespace app\module;
final class app_module_EventModel extends app_engine_Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$f = $this->table('event')->field('start', 'time');
		$f = $this->table('event')->field('end', 'time');
		$f = $this->table('event')->field('posted', 'time');
		
		$f = $this->table('event')->field('title', 'text');
		$f->regex = '#^[a-zA-Z \-\_\!\@\#\$\%\^\&\*\(\)\;\:\"\'\[\]\{\}\|\?\>\<\,\.\/0-9\~\`\=\+]+$#';
		$f = $this->table('event')->field('subtitle', 'text');
				
		$f = $this->table('event')->field('uname_id', 'pkey');
		
		$f = $this->table('event')->field('content_id', 'pkey');
		$f->length = 1000;
		
		$f = $this->table('event')->field('img_id', 'pkey');		
	}
}
?>