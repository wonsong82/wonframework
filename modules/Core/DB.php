<?php
class DB extends WonClass
{
	public $sql;
	public $prefix;
	
	protected function init(){}
			
	public function connect_sql($host, $user, $pass, $db)
	{
		if ($this->sql == null)
		{
			$this->sql = new MySQLi($host, $user, $pass, $db);
			$this->sql->set_charset('utf8');
		}
	}
	
	public function query($queryString) {
		
		$result = $this->sql->query($queryString) or die($this->sql->error);
		return $result;
	}
}
?>