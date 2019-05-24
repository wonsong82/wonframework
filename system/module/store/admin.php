<?php
// namespace app\module;
final class app_module_StoreAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		$page = $this->newPage('store-page');
		$page->text = $this->getText('General');
		$page->desc = $this->getText('general Information');
		$this->addPage($page);
		
		$name = $this->newTextField('store-name');
		$name->text = $this->getText('Store Name');
		$name->linkData($this->store->name);
		$name->action('store.update', 'store.name', $this->store->id, '#store-name');
		$page->addChild($name);
		
		// Phone
		$cc = $this->newTextField('pcc');
		$cc->text = $this->getText('Phone #');
		$cc->css = '#pcc-wrap.textfield-box{display:inline-block;margin-right:18px;}#pcc-wrap .textfield{width:30px;text-align:center;}';
		$cc->desc = $this->getText('Country Code');
		$cc->linkData($this->phonebook->select('phonebook.country_code', $this->store->phoneId));
		$cc->action('phonebook.update', 'phonebook.country_code', $this->store->phoneId, '#pcc');
		$page->addChild($cc);
		
		$ac = $this->newTextField('pac');
		$ac->css = '#pac-wrap.textfield-box{display:inline-block;}#pac-wrap .textfield{width:55px;text-align:center;}';
		$ac->linkData($this->phonebook->select('phonebook.area_code', $this->store->phoneId));
		$ac->action('phonebook.update', 'phonebook.area_code', $this->store->phoneId, '#pac');
		$ac->desc = $this->getText('Area Code');
		$page->addChild($ac);
		
		$prefix = $this->newTextField('pprefix');
		$prefix->css = '#pprefix-wrap.textfield-box{display:inline-block;}#pprefix-wrap .textfield{width:55px;text-align:center;}';
		$prefix->desc = $this->getText('Area Code');
		$prefix->linkData($this->phonebook->select('phonebook.prefix', $this->store->phoneId));
		$prefix->action('phonebook.update', 'phonebook.prefix', $this->store->phoneId, '#pprefix');
		$page->addChild($prefix);
		
		$number = $this->newTextField('pnumber');
		$number->css = '#pnumber-wrap.textfield-box{display:inline-block;}#pnumber-wrap .textfield{width:55px;text-align:center;}';
		$number->desc = $this->getText('Area Code');
		$number->linkData($this->phonebook->select('phonebook.number', $this->store->phoneId));
		$number->action('phonebook.update', 'phonebook.number', $this->store->phoneId, '#pnumber');
		$page->addChild($number);
		
		// Fax
		$cc = $this->newTextField('fcc');
		$cc->text = $this->getText('Fax #');
		$cc->css = '#fcc-wrap.textfield-box{display:inline-block;margin-right:18px;}#fcc-wrap .textfield{width:30px;text-align:center;}';
		$cc->desc = $this->getText('Country Code');
		$cc->linkData($this->phonebook->select('phonebook.country_code', $this->store->faxId));
		$cc->action('phonebook.update', 'phonebook.country_code', $this->store->faxId, '#fcc');
		$page->addChild($cc);
		
		$ac = $this->newTextField('fac');
		$ac->css = '#fac-wrap.textfield-box{display:inline-block;}#fac-wrap .textfield{width:55px;text-align:center;}';
		$ac->linkData($this->phonebook->select('phonebook.area_code', $this->store->faxId));
		$ac->action('phonebook.update', 'phonebook.area_code', $this->store->faxId, '#fac');
		$ac->desc = $this->getText('Area Code');
		$page->addChild($ac);
		
		$prefix = $this->newTextField('fprefix');
		$prefix->css = '#fprefix-wrap.textfield-box{display:inline-block;}#fprefix-wrap .textfield{width:55px;text-align:center;}';
		$prefix->desc = $this->getText('Area Code');
		$prefix->linkData($this->phonebook->select('phonebook.prefix', $this->store->faxId));
		$prefix->action('phonebook.update', 'phonebook.prefix', $this->store->faxId, '#fprefix');
		$page->addChild($prefix);
		
		$number = $this->newTextField('fnumber');
		$number->css = '#fnumber-wrap.textfield-box{display:inline-block;}#fnumber-wrap .textfield{width:55px;text-align:center;}';
		$number->desc = $this->getText('Area Code');
		$number->linkData($this->phonebook->select('phonebook.number', $this->store->faxId));
		$number->action('phonebook.update', 'phonebook.number', $this->store->faxId, '#fnumber');
		$page->addChild($number);
		
		$o = $this->newTextField('email');
		$o->text = $this->getText('Email');
		$o->desc = $this->getText('Email Address');
		$o->linkData($this->store->email);
		$o->action('store.update', 'store.email', $this->store->id, '#email');
		$page->addChild($o);
		
		$o = $this->newTextField('website');
		$o->text = $this->getText('Website');
		$o->desc = $this->getText('Website URL');
		$o->linkData($this->store->website);
		$o->action('store.update', 'store.website', $this->store->id, '#website');
		$page->addChild($o);
		
		
		// Address
		$label = $this->newTextField('address');
		$label->css = '#address-wrap{margin-top:20px;}';
		$label->static = true;
		$label->text = $this->getText('Address');
		$page->addChild($label);
		
		$f = $this->newTextField('street');
		$f->text = $this->getText('- street');
		$f->linkData($this->addressbook->select('addressbook.street', $this->store->addressId));
		$f->desc = $this->getText('Street');
		$f->action('addressbook.update', 'addressbook.street', $this->store->addressId, '#street');
		$page->addChild($f);
		
		$f = $this->newTextField('apt');
		$f->css = '#apt-wrap input{width:40px;}';
		$f->text = $this->getText('- apt #');
		$f->desc = $this->getText('Apartment #');
		$f->linkData($this->addressbook->select('addressbook.apt', $this->store->addressId));
		$f->action('addressbook.update','addressbook.apt',$this->store->addressId,'#apt');
		$page->addChild($f);
		
		$o = $this->newTextField('city');
		$o->text = $this->getText('- city');
		$o->desc = $this->getText('City Name');
		$o->linkData($this->addressbook->select('addressbook.city',$this->store->addressId));
		$o->action('addressbook.update','addressbook.city',$this->store->addressId,'#city');
		$page->addChild($o);
		
		$o = $this->newTextField('state');
		$o->text = $this->getText('- state');
		$o->desc = $this->getText('State Name');
		$o->linkData($this->addressbook->select('addressbook.state',$this->store->addressId));
		$o->action('addressbook.update','addressbook.state',$this->store->addressId,'#state');
		$o->css = '#state-wrap input{width:100px;}';
		$page->addChild($o);
		
		$o = $this->newTextField('zip');
		$o->text = $this->getText('- zip');
		$o->desc = $this->getText('Zip Code');
		$o->linkData($this->addressbook->select('addressbook.zip',$this->store->addressId));
		$o->action('addressbook.update','addressbook.zip',$this->store->addressId,'#zip');
		$o->css = '#zip-wrap input{width:70px}';
		$page->addChild($o);
		
		$o = $this->newTextField('country');
		$o->text = $this->getText('- country');
		$o->desc = $this->getText('Country Name');
		$o->linkData($this->addressbook->select('addressbook.country',$this->store->addressId));
		$o->action('addressbook.update','addressbook.country',$this->store->addressId,'#country');
		$o->css = '#country-wrap input{width:100px;}';
		$page->addChild($o);
		
		
		// Social Page
		$page = $this->newPage('social-page');
		$page->text = $this->getText('Social Links');
		$page->desc = $this->getText('List of the Social Links');
		$this->addPage($page);
		
		$o = $this->newTextField('facebook');
		$o->text = $this->getText('Facebook');
		$o->desc = $this->getText('Facebook Page URL');
		$o->linkData($this->store->facebook);
		$o->action('store.update', 'store.facebook', $this->store->id, '#facebook');
		$page->addChild($o);
		
		$o = $this->newTextField('twitter');
		$o->text = $this->getText('Twitter');
		$o->desc = $this->getText('Twitter Page URL');
		$o->linkData($this->store->twitter);
		$o->action('store.update', 'store.twitter', $this->store->id, '#twitter');
		$page->addChild($o);
		
		$o = $this->newTextField('yelp');
		$o->text = $this->getText('Yelp');
		$o->desc = $this->getText('Yelp Page URL');
		$o->linkData($this->store->yelp);
		$o->action('store.update', 'store.yelp', $this->store->id, '#yelp');
		$page->addChild($o);
		
		// Hours
		$page = $this->newPage('hours-page');
		$page->text = $this->getText('Store Hours');
		$page->desc = $this->getText('Start to Close. Leave both fields blanks for closing days.');
		$this->addPage($page);
		
		$hours = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		foreach($hours as $h){
			$sid = 'hours-'.strtolower($h).'-s';
			$eid = 'hours-'.strtolower($h).'-e';
			$s = 'hours'.$h.'S';
			$e = 'hours'.$h.'E';
			
			$o = $this->newTextField($sid);
			$o->text = $this->getText($h);
			$o->desc = $this->getText($h) . ' '. $this->getText('Openning Time');
			$o->linkData($this->store->$s);
			
			$o->action('store.update', 'store.hours_'.strtolower($h).'_s', $this->store->id, '#'.$sid);
			$o->css = 
				'#'.$sid.'-wrap{display:inline-block;}'.
				'#'.$sid.'-wrap input{width:50px;text-align:right;}';
			$page->addChild($o);
			
			$o = $this->newTextField($eid);
			$o->text = $this->getText(' - ');
			$o->desc = $this->getText($h) . ' '. $this->getText('Closing Time');
			$o->linkData($this->store->$e);
			
			$o->action('store.update', 'store.hours_'.strtolower($h).'_e', $this->store->id, '#'.$eid);
			$o->css = 
				'#'.$eid.'-wrap{display:inline-block;}'.
				'#'.$eid.'-wrap label{width:5px;}'.
				'#'.$eid.'-wrap input{width:50px;text-align:right;}';
			$page->addChild($o);
			$page->addChild('<br/>');
			
		}
		
	}
}
?>