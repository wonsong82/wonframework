<?php
// Name : User Model
// Desc : Controlls User Authentication and Roles
namespace app\module;
final class UserModel extends \app\engine\Model{
	
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		//user.username
		$field = $this->table('user')->field('username', 'text');
		$field->regex = '#^[a-zA-Z0-9]{5,20}$#';
		$field->key = 'unique';
		//user.password
		$field = $this->table('user')->field('password', 'text');
		$field->regex = '#^[a-zA-Z0-9\_\-\/\?\!\-\_]{3,}$#';
		//user.active
		$field = $this->table('user')->field('active', 'bool');
		$field->default = true;
		//user.banned
		$field = $this->table('user')->field('banned', 'bool');
		$field->default = false;
		//user.joined
		$field = $this->table('user')->field('joined', 'time');
		
		//usergroup.name
		$field = $this->table('usergroup')->field('name', 'text');
		$field->regex = '#^.+$#';
		$field->key = 'index';
		//usergroup.editable
		$field = $this->table('usergroup')->field('editable', 'bool');
		$field->default = true;
		
		//usermembership.userid
		$field = $this->table('usermembership')->field('userid', 'pkey');
		$field->key = 'index';
		//usermembership.groupid
		$field = $this->table('usermembership')->field('groupid', 'pkey');
		$field->key = 'index';
		
		//usermeta.default
		$field = $this->table('usermeta')->field('default_group', 'pkey');		
	}
}
?>