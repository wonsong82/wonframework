<?php
class ContactController extends Controller{
	
	public $contacts = array();
	
	protected function init(){
		$this->lib->import('util.format');
		$this->lib->import('external.googlemap');
		$contacts=$this->model->getContacts();
		foreach($contacts as $contact){
			$this->contacts[$contact['name']]=$contact;
		}		
	}
	
	public function getTel($name,$format='+{$country} ({$area}) {$first} - {$last}'){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contacts[$name];
		$bits['country']=$contact['countryCode'];
		$bits['area']=substr($contact['tel'],0,3);
		$bits['first']=substr($contact['tel'],3,3);
		$bits['last']=substr($contact['tel'],6,4);
		return $this->lib->format->text($bits,$format);		
	}
	
	public function getFax($name,$format='+{$country} ({$area}) {$first} - {$last}'){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}		
		$contact=$this->contacts[$name];
		$bits['country']=$contact['countryCode'];
		$bits['area']=substr($contact['fax'],0,3);
		$bits['first']=substr($contact['fax'],3,3);
		$bits['last']=substr($contact['fax'],6,4);
		return $this->lib->format->text($bits,$format);	
	}
	
	public function getEmail($name){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}
		return $this->contacts[$name]['email'];
	}
	
	public function getAddress($name,$format='{$address1}<br/>{$address2}'){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contacts[$name];
		$bits['address1']=$contact['address1'];
		$bits['address2']=$contact['address2'];
		return $this->lib->format->text($bits,$format);
	}
	
	public function getMaplink($name,$lang='en',$z=15){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contacts[$name];
		$address=$contact['address1'].' '.$contact['address2'];
		return $this->lib->googlemap->getMaplink($address,$lang,$z);
	}
	
	public function getMap($name,$width,$height,$lang='en',$z=15){
		if(!isset($this->contacts[$name])){
			$this->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contacts[$name];
		$address=$contact['address1'].' '.$contact['address2'];
		return $this->lib->googlemap->getEmbeddedMap($address,$width,$height,$lang,$z);
	}	
	
	
	
	public function addContact(){
		$this->model->add('contact');
		return true;
	}
	
	public function removeContact($id){
		$this->model->remove('contact',$id);
		return true;
	}
	
	public function updateName($id, $name){
		$id=(int)$id;
		if(!$this->model->validate('contact.name',$name)){
			$this->setError('INVALID_NAME');
			return false;
		}
		$this->model->update('contact.name',$id,$name);
		return true;				
	}
	
	public function updateCountryCode($id, $countryCode){
		$id=(int)$id;
		if(!$this->model->validate('contact.countryCode',$countryCode)){
			$this->setError('INVALID_COUNTRYCODE');
			return false;
		}
		$this->model->update('contact.countryCode',$id,$countryCode);
		return true;
	}
	
	public function updateTel($id, $tel){
		$id=(int)$id;
		if(!$this->model->validate('contact.tel',$tel)){
			$this->setError('INVALID_TEL');
			return false;
		}
		$this->model->update('contact.tel',$id,$tel);
		return true;
	}
	
	public function updateFax($id, $fax){
		$id=(int)$id;
		if(!$this->model->validate('contact.fax',$fax)){
			$this->setError('INVALID_FAX');
			return false;
		}
		$this->model->update('contact.fax',$id,$fax);
		return true;
	}
	
	public function updateEmail($id, $email){
		$id=(int)$id;
		if(!$this->model->validate('contact.email',$email)){
			$this->setError('INVALID_EMAIL');
			return false;
		}
		$this->model->update('contact.email',$id,$email);
		return true;
	}
	
	public function updateAddress1($id, $address1){
		$id=(int)$id;
		if(!$this->model->validate('contact.address1',$address1)){
			$this->setError('INVALID_ADDRESS1');
			return false;
		}
		$this->model->update('contact.address1',$id,$address1);
		return true;
	}
	
	public function updateAddress2($id, $address2){
		$id=(int)$id;
		if(!$this->model->validate('contact.address2',$address2)){
			$this->setError('INVALID_ADDRESS2');
			return false;
		}
		$this->model->update('contact.address2',$id,$address2);
		return true;
	}
}

?>