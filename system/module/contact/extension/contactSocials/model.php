<?php
class ContactSocialsModel extends ModelExtension{
	
	protected function init(){
		$this->setField('contact.facebook','facebook',"VARCHAR(255) DEFAULT ''", '#^https?://www.facebook.com/#');
		$this->setField('contact.twitter','twitter',"VARCHAR(255) DEFAULT ''",'#^https?://www.twitter.com/#');
		$this->setField('contact.yelp','yelp',"VARCHAR(255) DEFAULT ''",'#^https?://www.yelp.com/#');
		
		$this->setError('INVALID_FACEBOOK', 'Invalid facebook page URL');
		$this->setError('INVALID_TWITTER', 'Invalid twitter page URL');
		$this->setError('INVALID_YELP', 'Invalid yelp page URL');
	}
}
?>