<?php
final class Db {
	
	private $db;
	public $prefix;	
	
	public function __construct($registry) {
		
		$this->db=$registry->lib->load('db.mysql');
		$this->db->connect(
			$registry->config->dbHost,
			$registry->config->dbUser,
			$registry->config->dbPass,
			$registry->config->dbDb
		);
		$this->prefix = $registry->config->dbPrefix;
	}
	
	public function __call($func, $args) {
		return call_user_func_array(array($this->db, $func), $args);
	}
}
?>