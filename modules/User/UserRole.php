<?php

class UserRole
{
	private $table;
	private $relation_table;
	
	public function __construct()
	{
		// load config
		if (!defined('CONFIG_LOADED'))
			require_once '../../config.php';
			
		// initialize table
		$this->table = DB_PREFIX . 'user_role';
		$this->relation_table = DB_PREFIX . 'user_role_relationship';
		$this->initialize_table();			
		
	}
	
	private function initialize_table()
	{
		global $sql;
		
		$sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`name` VARCHAR (255) NOT NULL DEFAULT '',
				`allowed_roles` VARCHAR (255) NOT NULL DEFAULT '',
				PRIMARY KEY(`id`)							
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`		
		") or die($sql->error);
		
		$sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->relation_table}` (
				`id` SERIAL NOT NULL,
				`user_id` BIGINT,
				`role_id` BIGINT,
				PRIMARY KEY(`id`)							
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`		
		") or die($sql->error);
	}
	
	public function get_roles()
	{
		
	}

	public function add($name)
	{
		global $sql;
		
		$sql->query("
			INSERT INTO `{$this->table}`
			SET `name` = '$name'
		") or die ($sql->error);
		
		$sql->query("
			UPDATE `{$this->table}`
			SET `allowed_roles` = LAST_INSERT_ID()
			WHERE `id` = LAST_INSERT_ID()
		") or die($sql->error);
	}
	
	public function remove($id)
	{
		global $sql;
		
		$sql->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = $id
		") or die ($sql->error);
		
	}
	
	public function update($id, $key, $value)
	{
		global $sql;
		$value = $sql->real_escape_string($value);
		
		$sql->query("		
			UPDATE `{$this->table}`
			SET		`{$key}` = '{$value}'
			WHERE `id` = '{$id}'		
		") or die($sql->error);
	}
	
}

?>