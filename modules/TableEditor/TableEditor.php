<?php
class TableEditor
{
	
	public function __construct()
	{
		if (!defined('CONFIG_LOADED'))
			require_once '../../config.php';
	}
	
	public function list_tables()
	{
		$sql = Sql::sql();
		
		$tables = $sql->query("		
			SHOW TABLES;		
		") or die($sql->error);
		
		if ($tables->num_rows) 
		{
			$list = array();
			
			while ($table = $tables->fetch_row())
				$list[] = $table[0];
			
			return $list;
		}
		
		else 
			return false;
		
	}
	
	public function get_table_structure($table_name)
	{
		$sql = Sql::sql();
		
		$struct = $sql->query("		
			SHOW COLUMNS FROM `{$table_name}`			
		") or die ($sql->error);
		
		if ($struct->num_rows != 0)
		{
			$list = array();
			
			while ($row = $struct->fetch_assoc()) 
				$list[] = $row['Field'];
			
			return $list;
		}
		
		else 
			return false;
		
	}
	
	public function get_table($table_name)
	{
		$sql = Sql::sql();
		
		$table = $sql->query("		
			SELECT * FROM `{$table_name}`		
		") or die($sql->error);
		
				
		if ($table->num_rows != 0)
		{
			$list = array();
			
			while ($row = $table->fetch_assoc())
				$list[] = $row;
				
			return $list;
		}
		
		else
			return false;
	}
	
	public function update($table_name, $id, $key, $val)
	{
		$sql = Sql::sql();
		
		$val = $sql->real_escape_string(trim($val));
		
		$sql->query("		
			UPDATE `{$table_name}`
			SET `{$key}` = '{$val}'
			WHERE `id` = '$id'		
		") or die($sql->error);
	}
	
	public function delete($table_name, $id)
	{
		$sql = Sql::sql();
		
		$sql->query("			
			DELETE FROM `{$table_name}`
			WHERE `id` = '$id'		
		") or die($sql->error);
	}
	
	public function add($table_name, $param)
	{
		$sql = Sql::sql();
		
		$set = array();
		foreach ($param as $key=>$val) 
		{
			$val = $sql->real_escape_string(trim($val));			
			$set[] = "`{$key}` = '{$val}'";
		}
		$set = implode(', ' , $set);
		
		$sql->query("		
			INSERT INTO `{$table_name}`
			SET {$set}		
		") or die($sql->error);
	}
	
}


?>