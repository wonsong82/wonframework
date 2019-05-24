<?php
// Name : Addressbook Model
// Desc : Addressbook & Fax Book

// namespace app\module;
final class app_module_AddressbookModel extends app_engine_Model {
	
	//
	// DB Structure
	public function __construct($reg){
		
		// Call parent's Construct
		parent::__construct($reg);
		
		//
		// Structure the Database
		
		$f = $this->table('addressbook')->field('street', 'text');
		$f = $this->table('addressbook')->field('apt', 'text');
		$f = $this->table('addressbook')->field('city', 'text');
		$f = $this->table('addressbook')->field('state', 'text');
		$f = $this->table('addressbook')->field('zip', 'text');
		$f = $this->table('addressbook')->field('country', 'text');	
	}
	
}
?>