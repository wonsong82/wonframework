<?php
class Sqlite {
	
	public $result;
	private $link;
	
	
	public function connect($dbPath, $mode=0666){
		// PDO SQLITE3
		$this->link = new PDO("sqlite:".$dbPath);		
	}
	
	public function query($query){
		$stmt = $this->link->prepare($query);
		if($stmt->execute()) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;		
	}
	
	public function escape($value){
		return $this->link->quote($value);
	}
}
?>