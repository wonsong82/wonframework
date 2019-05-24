<?php
// Name : User Model
// Desc : Controlls User Authentication and Roles
namespace app\module;
final class MailModel extends \app\engine\Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$field = $this->table('mail')->field('is_smtp', 'bool');
		$field->default = false;
		
		$field = $this->table('mail')->field('host', 'text');
				
		$field = $this->table('mail')->field('secure', 'text');
		
		$field = $this->table('mail')->field('is_auth', 'bool');
		$field->default = false;
		
		$field = $this->table('mail')->field('auth_user', 'text');
		$field = $this->table('mail')->field('auth_pass', 'text');
		
		$field = $this->table('mail')->field('port', 'int');				
	}
}
?>