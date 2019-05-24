<?php
namespace com\won\database;
final class MySQLManager{
	
	private $sql;
	
	public function __construct(MySQL $mySQL){
		$this->sql = $mySQL;
	}
	
	public function backupDB($sqlFile){
		$tables=$this->sql->query("SHOW TABLES", 'row');
		$sql='';
		foreach($tables as $table){
			$table=$table[0];
			
			// Drop Table
			$sql.="--\n-- Table structure for table `{$table}`\n--\n\n";
			$sql.="DROP TABLE IF EXISTS `{$table}`;";
			
			// Create Table
			$structure=$this->sql->query("SHOW CREATE TABLE `{$table}`");
			$structure=$structure[0]['Create Table'];
			$sql.="\n\n{$structure};\n\n";
			
			// Values
			$rows=$this->sql->query("SELECT * FROM `{$table}`", 'row');
			$sql.="--\n-- Dumping data for table `{$table}`\n--\n\n";
			if(count($rows)){
				$sql.="INSERT INTO `{$table}` VALUES\n";
				foreach ($rows as &$row){
					foreach ($row as $f=>&$v){
						$v=addslashes($v);
						$v=str_replace("\n","\\n",$v);
						$v="'".$v."'";											
					}
					$row='('.implode(',',$row).')';
				}
				$sql.=implode(",\n", $rows);
				$sql.=";";			
			}
			$sql.="\n\n-- ---------------------------------------------------\n\n";			
		}
		$handle=fopen($sqlFile, 'w+');
		fwrite($handle,$sql);
		fclose($handle);
	}
	
	public function restoreDB($sqlFile){
		$sql = fread(fopen($sqlFile, 'r'), filesize($sqlFile)) or die('Could not read the SQLFile');
		$lines = explode("\n", $sql);
		$temp='';
		foreach ($lines as $line){
			if(substr($line,0,2)=='--' || $line=='')
				continue;
			$temp.=$line;
			if(substr(trim($line), -1, 1)==';'){
				$this->sql->query($temp);
				$temp='';
			}			
		}
	}
	
	
}
?>