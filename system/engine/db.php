<?php
// Name : Db
// Desc : db instance being used through the App.

// namespace app\engine;
final class app_engine_Db {
	
	private $reg;
	private $dbObj;
	
	
	//
	// Constructor
	// Initialize the Database Obj (MySQL in this App)
	public function __construct($reg) {		
		$this->reg = $reg;
		$this->dbObj = $this->reg->loader->getClass('database.MySQL'); 
		if(!$this->dbObj->connect(
			$this->reg->config->dbHost,
			$this->reg->config->dbUser,
			$this->reg->config->dbPass,
			$this->reg->config->dbDb
		))
			die("Database Error");
	}
	
	//
	// Backup the Mysql Database
	//
	public function backup($sqlFile){
		$dbManager = $this->reg->loader->getClass('database.MySQLManager', $this->dbObj);
		$dbManager->backupDB($sqlFile);
	}
	
	//
	// Restore the Mysql Database
	//
	public function restore($sqlFile){
		$dbManager = $this->reg->loader->getClass('database.MySQLManager', $this->dbObj);
		$dbManager->restoreDB($sqlFile);
	}
	
	//
	// Call the functions in MySQL
	//
	public function __call($func, $args) {
		return call_user_func_array(array($this->dbObj, $func), $args);
	}
	
	public function lastQuery(){
		return $this->dbObj->lastQuery;
	}
	
	public function lastError(){
		return $this->dbObj->lastError;
	}
	
	public function escape($string){
		return str_replace('[','&#91;',str_replace(']','&#93;',$this->dbObj->escape($string)));
	}
	
	
}
?>