<?php
class ContactSocialsController extends ControllerExtension{
	
	public function getFacebook($contactName){
		if(!isset($this->contact->contacts[$contactName])){
			$this->contact->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contact->contacts[$contactName];
		return $contact['facebook'];
	}
	
	public function getYelp($contactName){
		if(!isset($this->contact->contacts[$contactName])){
			$this->contact->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contact->contacts[$contactName];
		return $contact['yelp'];
	}
	
	public function getTwitter($contactName){
		if(!isset($this->contact->contacts[$contactName])){
			$this->contact->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contact->contacts[$contactName];
		return $contact['twitter'];
	}
	
	
	public function updateFacebook($id,$facebookLink){
		$id=(int)$id;
		if(!$this->model->validate('contact.facebook',$facebookLink)){
			$this->setError('INVALID_FACEBOOK');
			return false;
		}
		$this->model->update('contact.facebook',$id,$facebookLink);
		return true;
	}
	
	public function updateTwitter($id,$twitterLink){
		$id=(int)$id;
		if(!$this->model->validate('contact.twitter',$twitterLink)){
			$this->setError('INVALID_TWITTER');
			return false;
		}
		$this->model->update('contact.twitter',$id,$twitterLink);
		return true;
	}
	
	public function updateYelp($id,$yelpLink){
		$id=(int)$id;
		if(!$this->model->validate('contact.yelp',$yelpLink)){
			$this->setError('INVALID_YELP');
			return false;
		}
		$this->model->update('contact.yelp',$id,$yelpLink);
		return true;
	}
}
?>