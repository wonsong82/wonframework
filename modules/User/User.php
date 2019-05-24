<?php

class User
{
	private $table;	
	public $error;
	
	
	public function __construct()
	{
		// load config
		if (!defined('CONFIG_LOADED'))
			require_once '../../config.php';
			
		// initialize table
		$this->table = DB_PREFIX . 'user';
		$this->initialize_table();			
		
	}
	
	private function initialize_table()
	{
		global $sql;
		
		$sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`username` VARCHAR(255) NOT NULL DEFAULT '',
				`password` VARCHAR(255) NOT NULL DEFAULT '',
				`joined` DATETIME NOT NULL DEFAULT '',
				`logged` BIGINT UNSIGNED DEFAULT 0,
				PRIMARY KEY(`id`)							
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`			
		") or die($sql->error);

	}
	
	public function add_user($username, $password)
	{		
		// validate username & password fields
		if (!preg_match('#^[a-z0-9_-]{5,15}$#', $username))
		{
			$this->error = 'username must contain only lowercase letters, numbers, - and _, between 5-15 chars';
			return false;
		}
		
		
		
		
		
		// check existing username
		
		
		
		// add to user table	
		
	}
	
	
	
}

?>