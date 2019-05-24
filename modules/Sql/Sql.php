<?php
class Sql
{
	private static $_sql;
	private static $_prefix;
	
	public function __construct(){}
	
	public static function connect($host, $user, $pass, $db)
	{
		if (Sql::$_sql == null)
		{ 
			Sql::$_sql = new MySQLi($host, $user, $pass, $db);		
			Sql::$_sql->set_charset('utf8');
		}
	}	
	
	public static function sql()
	{
		return Sql::$_sql;
	}
	
	public static function prefix($val=null)
	{
		if ($val==null)
			return Sql::$_prefix;
		else
			Sql::$_prefix = $val;
	}
}
?>