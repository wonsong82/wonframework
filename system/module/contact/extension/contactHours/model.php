<?php
class ContactHoursModel extends ModelExtension{
	
	protected function init(){
		
		$this->setField('contact.hoursMonStart','hours_mon_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursMonEnd','hours_mon_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursTueStart','hours_tue_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursTueEnd','hours_tue_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursWedStart','hours_wed_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursWedEnd','hours_wed_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursThuStart','hours_thu_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursThuEnd','hours_thu_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursFriStart','hours_fri_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursFriEnd','hours_fri_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursSatStart','hours_sat_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursSatEnd','hours_sat_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursSunStart','hours_sun_s',"VARCHAR(255) NOT NULL DEFAULT '9am'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		$this->setField('contact.hoursSunEnd','hours_sun_e',"VARCHAR(255) NOT NULL DEFAULT '7pm'",'#^[0-9] ?[ap]m$|^1[012] ?[ap]m$|^[0-9]:[0-5][0-9] ?[ap]m$|^1[012]:[0-5][0-9] ?[ap]m#i');
		
		$this->setError('INVALID_TIME', 'invalid time format.');
	}
}
?>