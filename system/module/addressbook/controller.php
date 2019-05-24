<?php
// Name : Addressbook Controller
// Desc : 

// namespace app\module;
final class app_module_AddressbookController extends app_engine_Controller{
	
	// @override
	// Constructor
	public function __construct($reg){
		// Call Parent's Constructor First
		parent::__construct($reg);
		
		
	}
	
	//
	// Get Address of ID
	public function getAddress($id){
		// Escape ID
		$id = (int)$this->db->escape($id);
		// Get Address
		$result = $this->model->query("
			SELECT	[addressbook.street] AS [street],
					[addressbook.apt] AS [apt],
					[addressbook.city] AS [city],
					[addressbook.state] AS [state],
					[addressbook.zip] AS [zip],
					[addressbook.country] AS [country]
			FROM [addressbook]
			WHERE [addressbook.id] = {$id}
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
	// Get Address of ID
	// $format, s = street, a = apt, c = city, t = state, z = zip, y = country
	public function formatAddress($id, $format='s a\\nc, t z'){
		$result = $this->getAddress($id);
		if($result===false){
			return false;
		}
		return	str_replace('s', $result['street'],
				str_replace('a', $result['apt'],
				str_replace('c', $result['city'],
				str_replace('t', $result['state'],
				str_replace('z', $result['zip'],
				str_replace('y', $result['country'],
				$format))))));
	}
	
	//
	// Get Map Link
	public function getGoogleLink($id, $lan='en'){
		$address = $this->getAddress($id);
		if($address===false){
			return false;
		}
		switch ($lan){
			case 'en' :
				$http = 'http://maps.google.com';
				break;
			case 'ko' :
				$http = 'http://maps.google.co.kr';
				break;
			default :
				$http = 'http://maps.google.com';
				break;
		}
		
		$address = str_replace(' ','+',$address['street'].' '.$address['city'].', '.$address['state'].' '.$address['zip'].' '.$address['country']);
				
		return $http.'/maps?f=q&source=s_q&hl='.$lan.'&q='.$address.'&z=16';
	}
	
	//
	// Get Embedded Map
	public function getGoogleMap($id, $width, $height, $lan='en'){
		$address = $this->getAddress($id);
		switch ($lan){
			case 'en' :
				$http = 'http://maps.google.com';
				break;
			case 'ko' :
				$http = 'http://maps.google.co.kr';
				break;
			default :
				$http = 'http://maps.google.com';
				break;
		}
		
		$address = str_replace(' ','+',$address['street'].' '.$address['city'].', '.$address['state'].' '.$address['zip'].' '.$address['country']);
		
		return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$http.'/maps?f=q&source=s_q&hl='.$lan.'&q='.$address.'&z=15&output=embed"></iframe>';
	}
	
	
	
	//
	// Add an Address
	public function add($street, $apt, $city, $state, $zip, $country='US'){
		
		// Validate
		$val=array();		
		$val[]=$this->model->field('addressbook.street')->validate($street);
		$val[]=$this->model->field('addressbook.apt')->validate($apt);
		$val[]=$this->model->field('addressbook.city')->validate($city);
		$val[]=$this->model->field('addressbook.state')->validate($state);
		$val[]=$this->model->field('addressbook.zip')->validate($zip);
		$val[]=$this->model->field('addressbook.country')->validate($country);
		foreach($val as $v){
			if(!$v){
				$this->error = 'Invalid Address Format';
				return false;
			}
		}
		
		// Add
		$street = $this->db->escape($street);
		$apt = $this->db->escape($apt);
		$city = $this->db->escape($city);
		$state = $this->db->escape($state);
		$zip = $this->db->escape($zip);
		$country = $this->db->escape($country);
		$order = $this->nextOrder('addressbook');
		$result = $this->model->query("
			INSERT INTO [addressbook]
			SET	[addressbook.street] = '{$street}',
				[addressbook.apt] = '{$apt}',
				[addressbook.city] = '{$city}',
				[addressbook.state] = '{$state}',
				[addressbook.zip] = '{$zip}',
				[addressbook.country] = '{$country}',
				[order] = {$order}
		");
		
		if(false===$result){
			$this->error =$this->db->lastError();
			return false;
		}
		
		return true;		
	}	
	
	//
	// Remove the Address
	public function remove($id){
		// Escape ID
		$id = (int)$this->db->escape($id);
		$result = $this->model->query("
			DELETE FROM [addressbook]
			WHERE [addressbook.id] = {$id}
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