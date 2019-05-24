<?php
// Name : Phonebook Controller
// Desc : 

// namespace app\module;
final class app_module_PhonebookController extends app_engine_Controller{
	
	// @override
	// Constructor
	public function __construct($reg){
		// Call Parent's Constructor First
		parent::__construct($reg);
		
		
	}
	
	//
	// Get Number of ID
	public function getNumber($id){
		// Escape ID
		$id = (int)$this->db->escape($id);
		
		// Get Number
		$result = $this->model->query("
			SELECT 	[phonebook.country_code] AS [country_code],
					[phonebook.area_code] AS [area_code],
					[phonebook.prefix] AS [prefix],
					[phonebook.number] AS [number]
			FROM [phonebook]
			WHERE [phonebook.id] = {$id}
		");
		
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		if(!count($result)){
			$this->error = 'Unexisting ID';
			return false;
		}
		
		return $result[0];
	}
	
	//
	// Get phone number of ID
	// $format , c = countrycode, a = areacode, p = prefix, n = number
	public function formatNumber($id, $format='capn'){
		$result = $this->getNumber($id);
		if($result===false){
			return false;
		}
		return	str_replace('c', $result['country_code'],
				str_replace('a', $result['area_code'],
				str_replace('p', $result['prefix'],
				str_replace('n', $result['number'],
				$format))));	
	}
	
	
	//
	// Add a phone number
	public function add($cc, $area, $prefix, $number){
		// Validate the inputs
		$v1=$this->model->field('phonebook.country_code')->validate($cc);
		$v2=$this->model->field('phonebook.area_code')->validate($area);
		$v3=$this->model->field('phonebook.prefix')->validate($prefix);
		$v4=$this->model->field('phonebook.number')->validate($number);
		if(!$v1||!$v2||!$v3||!$v4){
			$this->error = 'The Number is in Invalid Format.';
			return false;
		}
		
		/*
		// Check for Duplicates
		$cc = $this->db->escape($cc);
		$area = $this->db->escape($area);
		$prefix = $this->db->escape($prefix);
		$number = $this->db->escape($number);
		
		$result = $this->model->query("
			SELECT [phonebook.id]
			FROM [phonebook]
			WHERE [phonebook.country_code] = {$cc}
				AND [phonebook.area_code] = {$area}
				AND [phonebook.prefix] = {$prefix}
				AND [phonebook.number] = {$number}
		");
		
		
		// If DB Error
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		// If Duplicate Exist
		if(count($result)){
			$this->error = 'Existing Number Entree';
			return false;
		}*/
		
		// Add
		$order = $this->nextOrder('phonebook');
		$result = $this->model->query("
			INSERT INTO [phonebook]
			SET	[phonebook.country_code] = '{$cc}',
				[phonebook.area_code] = '{$area}',
				[phonebook.prefix] = '{$prefix}',
				[phonebook.number] = '{$number}',
				[order] = {$order}
		");
		
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		return true;
	}
	
	//
	// Remove the number
	public function remove($id){
		// Escape ID
		$id = (int)$this->db->escape($id);
		$result = $this->model->query("
			DELETE FROM [phonebook]
			WHERE [phonebook.id] = {$id}
		");
		// DB Error Handler
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	
}
?>