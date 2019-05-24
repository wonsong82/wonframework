<?php
// Name : Contact Model
// Desc : Information and shortcuts about Contact information, single or multiple

// namespace app\module;
final class app_module_StoreModel extends app_engine_Model {
	
	//
	// DB Structure
	public function __construct($reg){
		
		// Call parent's Construct
		parent::__construct($reg);
		
		$f = $this->table('store')->field('name', 'text');
		$f->default = 'Store Name';
		$f->key = 'unique';
		
		$f = $this->table('store')->field('phone', 'pkey');
		$f = $this->table('store')->field('fax', 'pkey');
		$f = $this->table('store')->field('address', 'pkey');
		
		$f = $this->table('store')->field('email', 'text');
		$f->regex = '#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$#';
		$f->default = 'info@email.com';
		
		$f = $this->table('store')->field('website', 'text');
		$f->regex = '#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i';
		$f->default = 'http://www.website.com';
		
		$f = $f->lang('ko');
		$f->regex = '#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i';
		$f->default = 'http://www.website.com/ko/';
					
		$f = $this->table('store')->field('facebook', 'text');
		$f = $this->table('store')->field('twitter', 'text');
		$f = $this->table('store')->field('yelp', 'text');
		
		$r = '#^(([0-2])?[0-9](\:[0-5][0-9])* ?(am|AM|pm|PM)?)?$#';
		$f = $this->table('store')->field('hours_mon_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_mon_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_tue_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_tue_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_wed_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_wed_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_thu_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_thu_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_fri_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_fri_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_sat_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_sat_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';
		$f = $this->table('store')->field('hours_sun_s', 'text');
		$f->regex = $r;
		$f->default = '9am';
		$f = $this->table('store')->field('hours_sun_e', 'text');
		$f->regex = $r;
		$f->default = '7pm';	
	}
	
}
?>