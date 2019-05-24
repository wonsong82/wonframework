<?php
class ContactModel extends Model {
	
	protected function init(){
		$this->lib->import('util.regex');
		
		$this->setField('contact.name','name',"VARCHAR(255) DEFAULT 'new name'",'#^.+$#');
		$this->setField('contact.countryCode','country_code','INT(3) DEFAULT 1', '#^[0-9]+$#');
		$this->setField('contact.tel','tel','INT(15) DEFAULT 2120010001','#[0-9]+$#');
		$this->setField('contact.fax','fax','INT(15) DEFAULT 2120010002','#[0-9]+$#');
		$this->setField('contact.email','email',"VARCHAR(255) DEFAULT ''",$this->lib->regex->email);
		$this->setField('contact.address1','address1',"VARCHAR(255) DEFAULT 'address line 1'",'#^.+$#');
		$this->setField('contact.address2','address2',"VARCHAR(255) DEFAULT 'address line 2'",'#^.*$#');
		
		$this->setError('INVALID_NAME', 'Invalid name.');
		$this->setError('INVALID_COUNTRYCODE', 'Invalid country code.');
		$this->setError('INVALID_TEL', 'Invalid phone number.');
		$this->setError('INVALID_FAX', 'Invalid fax number.');
		$this->setError('INVALID_EMAIL', 'Invalid email address.');
		$this->setError('INVALID_ADDRESS1', 'Invalid address line 1.');
		$this->setError('INVALID_ADDRESS2', 'Invalid address line 2.');
	}
	
	public function getContacts(){
		$table = $this->getTableName('contact');
		$fields = $this->getFields('contact');
		$contacts = $this->db->query("
			SELECT {$fields} FROM `{$table}`
			ORDER BY `id`
		");
		return $contacts;
	}
			
	public function getContactByName($name){
		$table = $this->getTableName('contact');
		$fields = $this->getFields('contact');
		$nameField = $this->getFieldName('contact.name');
		$name = $this->db->escape($name);
		$contact= $this->db->query("
			SELECT {$fields} FROM `{$table}`
			WHERE `{$nameField}`='{$name}'
			LIMIT 1
		");
		return $contact[0];
	}
	
	
	
}
?>