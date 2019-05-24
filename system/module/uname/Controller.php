<?php
// namespace app\module;
final class app_module_UnameController extends app_engine_Controller{
	
	public function get($param){
		if((int)$param > 0){
			$id = (int)$param;
			$where = "[uname.id] = {$id}";
		}
		else {
			$name = $this->db->escape($param);
			$where = "[uname.name] = '{$name}'";
		}
		$result = $this->model->query("
			SELECT 	[uname.id] AS [id],
					[uname.name] AS [name]
			FROM [uname]
			WHERE {$where}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Unexisting Unique Name';
			return false;
		}
		return $result[0];
	}
	
	public function uName($name){
		$name = strtolower($this->db->escape($name)); 
		$name = preg_replace('#[^a-z0-9-_\s\/]#s','',$name); // remove special chars
		$name = preg_replace('#\s{2,}#',' ', $name); // one space only
		$name = str_replace(' ','-', $name);
		$name = rtrim($name, '/');
		
		// Find Duplicates	
		$nextName = false;	
		$result = $this->model->query("
			SELECT [uname.id] AS [id]
			FROM [uname]
			WHERE [uname.name] = '{$name}'
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}	
		
		$i = 2;
		while(count($result) > 0){
			$nextName = $name . '-' . $i;
			$result = $this->model->query("
				SELECT [uname.id] AS [id]
				FROM [uname]
				WHERE [uname.name] = '{$nextName}'
			");
			if(false===$result){
				$this->error =$this->db->lastError();
				return false;
			}
			$i++;
		}
		
		return $nextName? $nextName : $name;	
	}
	
	public function add($name){
		$uname = $this->uName($name);
		$result = $this->model->query("
			INSERT INTO [uname]
			SET [uname.name] = '{$uname}'
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	public function remove($param){
		if((int)$param > 0){
			$id = $this->db->escape((int)$param);
			$where = "[uname.id] = {$id}";
		} else {
			$name = $this->db->escape($param);
			$where = "[uname.name] = '{$name}'";
		}
		$result = $this->model->query("
			DELETE FROM [uname]
			WHERE {$where}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;		
	}
	
	// @override
	public function update($id, $name, $spacer=null){
		$id = (int)$this->db->escape($id);
		// First Remove The Current One First
		$result = $this->model->query("
			SELECT [uname.id] AS [id]
			FROM [uname]
			WHERE [uname.id] = {$id}
		");
		if(false==$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Invaid id';
			return false;
		}
		$result = $this->remove($id);
		if(!$result) 
			return false;
		
		$uname = $this->uName($name);
		$result = $this->model->query("
			INSERT INTO [uname]
			SET [uname.id] = {$id},
				[uname.name] = '{$uname}'
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;			
	}
	
	
}
?>