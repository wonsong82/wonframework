<?php
// Name : Phonebook Model
// Desc : Phonebook & Fax Book

// namespace app\module;
final class app_module_PhonebookModel extends app_engine_Model {
	
	//
	// DB Structure
	public function __construct($reg){
		
		// Call parent's Construct
		parent::__construct($reg);
		
		//
		// Structure the Database
		
		// Country Code
		$f = $this->table('phonebook')->field('country_code', 'text');
		$f->regex = '#^[0-9]+$#';
		
		$f = $this->table('phonebook')->field('area_code', 'text');
		$f->regex = '#^[0-9]{3,4}$#';
				
		$f = $this->table('phonebook')->field('prefix', 'text');
		$f->regex = '#^[0-9]{3,4}$#';
				
		$f = $this->table('phonebook')->field('number', 'text');
		$f->regex = '#^[0-9]{4}$#';
		
	}
	
}
?>