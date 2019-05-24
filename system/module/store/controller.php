<?php
// Name : Store Controller
// Desc : Store Info

// namespace app\module;
final class app_module_StoreController extends app_engine_Controller{
	
	// @override
	// Constructor
	public function __construct($reg){
		parent::__construct($reg);
		
		// Get Store Info
		$result = $this->model->query("
			SELECT 	[store.id] AS [id],
					[store.name] AS [name],
					[store.phone] AS [phoneId],
					[store.fax] AS [faxId],
					[store.address] AS [addressId],
					[store.website] AS [website],
					[store.email] AS [email],
					[store.facebook] AS [facebook],
					[store.twitter] AS [twitter],
					[store.yelp] AS [yelp],
					[store.hours_mon_s] AS [hoursMonS],
					[store.hours_mon_e] AS [hoursMonE],
					[store.hours_tue_s] AS [hoursTueS],
					[store.hours_tue_e] AS [hoursTueE],
					[store.hours_wed_s] AS [hoursWedS],
					[store.hours_wed_e] AS [hoursWedE],
					[store.hours_thu_s] AS [hoursThuS],
					[store.hours_thu_e] AS [hoursThuE],
					[store.hours_fri_s] AS [hoursFriS],
					[store.hours_fri_e] AS [hoursFriE],
					[store.hours_sat_s] AS [hoursSatS],
					[store.hours_sat_e] AS [hoursSatE],
					[store.hours_sun_s] AS [hoursSunS],
					[store.hours_sun_e] AS [hoursSunE]
			FROM [store]				
		");
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		$store = $result[0];
		foreach($store as $key=>$val){
			$this->$key = $val;
		}
	}
	
	// @override
	// Add more sutffs when updating DB
	public function updateDB(){
		parent::updateDB();
		// Check for entree
		$value = $this->model->query("
			SELECT [store.id] AS [id] FROM [store] 
		");
		if(false===$value){
			$this->error = $this->db->lastError();
			return false;
		}
		
		
		if(!count($value)){
			// Phone
			$this->phonebook->add('1','000','000','0000');
			$phoneID = $this->db->insertId();
			var_dump($this->db->lastError());
				
			// Fax
			$this->phonebook->add('1','000','000','0000');
			$faxID = $this->db->insertId();
			var_dump($this->db->lastError());
			
			// Address
			$this->addressbook->add('1 main st.', '', 'New York', 'NY', '10000', 'US');
			$addressID = $this->db->insertId();
			var_dump($this->db->lastError());
			
				
			$result = $this->model->query("
				INSERT INTO [store]
				SET	[store.phone] = {$phoneID},
					[store.fax] = {$faxID},
					[store.address] = {$addressID}					 
			");
			if(false===$result){
				$this->error = $this->db->lastError();
				return false;
			}
		}
		
	}
	
	public function getPhone($format='capn'){
		return $this->phonebook->formatNumber($this->phoneId, $format);
	}
	
	public function getFax($format='capn'){
		return $this->phonebook->formatNumber($this->faxId, $format);
	}
	
	public function getAddress($format='s a\\nc, t z'){
		return $this->addressbook->formatAddress($this->addressId, $format);
	}
	
	public function getMaplink(){
		return $this->addressbook->getGoogleLink($this->addressId);
	}
	
	public function getMap($width,$height){
		return $this->addressbook->getGoogleMap($this->addressId, $width, $height);
	}
	
	public function getHours($format = 'start - end', $combine=false, $combineFormat = 'start~end') {
		
		$days = array('mon','tue','wed','thu','fri','sat','sun');
		foreach ($days as $day) {
			$s = 'hours'.ucwords($day).'S';
			$e = 'hours'.ucwords($day).'E';
			if ($this->$s && $this->$e) {
				$hours[$day] = str_replace('start',$this->$s, str_replace('end',$this->$e, $format)); 
			}
		}
		
		if ($combine) {
			$data = array();
			foreach ($hours as $day=>$hour) {
				if (count($data)==0) { // if mon
					$data[] = array('days'=>array($day), 'hour'=>$hour);
				}
				else { // if not mon
					if ($data[count($data)-1]['hour']==$hour) {
						$data[count($data)-1]['days'][] = $day;
					}
					else {
						$data[] = array('days'=>array($day), 'hour'=>$hour);
					}
				}
			}
			
			$hours = array();
			foreach ($data as $d) {
				if (count($d['days'])>1) {
					$s = $d['days'][0];
					$e = $d['days'][count($d['days'])-1];
					$ds = str_replace('start',$s, str_replace('end',$e,$combineFormat));
					$hours[$ds] = $d['hour'];
				}
				else {
					$hours[$d['days'][0]] = $d['hour'];
				}
			}
		}
		
		return $hours;
	}
	
	function getFacebookLike($page) {
		
		$fb = '<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>';
		$fb .= '<fb:like id="fb-like" href="'.$page.'" layout="button_count" show_faces="false" width="80"></fb:like>';
		return $fb;
	}
	
	function getTwitterLike($page, $name) {
		
		$tw = '<span id="tw-like"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$page.'" data-text="'.$name.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>';
		return $tw;
	}	
}
?>