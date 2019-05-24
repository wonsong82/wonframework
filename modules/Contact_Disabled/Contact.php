<?php
class Contact extends WonClass
{
	/**
	 * @name $name
	 * @desc Name of the contact
	 * @type string
	 */
	public $name;
	
	
	/**
	 * @name $phone_a
	 * @desc Phone Area Code
	 * @type string
	 */
	public $phone_a;
	
	
	/**
	 * @name $phone_f
	 * @desc Phone First Number
	 * @type string
	 */
	public $phone_f;
	
	
	/**
	 * @name $phone_l
	 * @desc Phone Last Number
	 * @type string
	 */
	public $phone_l;
	
	
	/**
	 * @name $fax_a
	 * @desc Fax Area Code
	 * @type string
	 */
	public $fax_a;
	
	
	/**
	 * @name $fax_f
	 * @desc Fax First Number
	 * @type string
	 */
	public $fax_f;
	
	
	/**
	 * @name $fax_l
	 * @desc Fax Last Number
	 * @type string
	 */
	public $fax_l;
	
	
	/**
	 * @name $street
	 * @desc Street
	 * @type string
	 */
	public $street;
	
	
	/**
	 * @name $apt
	 * @desc Apartment
	 * @type string
	 */
	public $apt;
	
	/**
	 * @name $city
	 * @desc City
	 * @type string
	 */
	public $city;
	
	
	/**
	 * @name $state
	 * @desc State
	 * @type string
	 */
	public $state;
	
	
	/**
	 * @name $country
	 * @desc Country
	 * @type string
	 */
	public $country;
	
	
	/**
	 * @name $zip
	 * @desc Zip code
	 * @type string
	 */
	public $zip;
	
	
	/**
	 * @name $email
	 * @desc Email Address
	 * @type string
	 */
	public $email;
	
	
	/**
	 * @name $website
	 * @desc Website URL
	 * @type string
	 */
	public $website;
	
	/**
	 * @name $table
	 * @desc Contact's Table
	 * @type string
	 */
	private $table;
	
	
	
	/**
	 * @name initialize()
	 * @desc Initialize tables and insert the pre-values
	 * @param none	 
	 * @return void
	 */
	protected function init()
	{		
		$this->table = Won::get('DB')->prefix . 'contact';		
		
		Won::get('DB')->sql->query("
			
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`name` VARCHAR(255) NOT NULL DEFAULT '',
				`phone_a` VARCHAR(3) NOT NULL DEFAULT '',
				`phone_f` VARCHAR(3) NOT NULL DEFAULT '',
				`phone_l` VARCHAR(4) NOT NULL DEFAULT '',
				`fax_a` VARCHAR(3) NOT NULL DEFAULT '',
				`fax_f` VARCHAR(3) NOT NULL DEFAULT '',
				`fax_l` VARCHAR(4) NOT NULL DEFAULT '',
				`street` VARCHAR(255) NOT NULL DEFAULT '',
				`apt` VARCHAR(255) NOT NULL DEFAULT '',
				`city` VARCHAR(255) NOT NULL DEFAULT '',
				`state` VARCHAR(2) NOT NULL DEFAULT '',
				`country` VARCHAR(255) NOT NULL DEFAULT '',
				`zip` VARCHAR(10) NOT NULL DEFAULT '',
				`email` VARCHAR(255) NOT NULL DEFAULT '',
				`website` VARCHAR(255) NOT NULL DEFAULT '',
				PRIMARY KEY(`id`)							
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`	
		
		") or die(Won::get('DB')->sql->error);
		
		// initialize the table
		$table = Won::get('DB')->sql->query("SELECT count(`id`) as `length` FROM `{$this->table}` ") or die(Won::get('DB')->sql->error);
		$table = $table->fetch_assoc();
						
		if ($table['length'] == 0)
		{	
			Won::get('DB')->sql->query("
			
				INSERT INTO `{$this->table}`
				SET		`name` = 'My Name',
						`phone_a` = '000',
						`phone_f` = '000',
						`phone_l` = '0000',
						`fax_a` = '000',
						`fax_f` = '000',
						`fax_l` = '0000',
						`street` = '1 Main Street',
						`apt` = '',
						`city` = 'New York',
						`state` = 'NY',
						`country` = 'USA',
						`zip` = '10001',
						`email` = 'aaa@email.com',
						`website` = 'http://www.website.com'					
			
			") or die(Won::get('DB')->sql->error);
		}
		
		$this->refresh_list();
	}
		
	
	
	/**
	 * @name validate(string $key, string $value)	 
	 * @desc Validate the input values (name, phone, fax, address, email, website) and returns true or false. 
	 * @param string $key : Validate property.
	 * @param string $value : Value of the property.
	 * @return bool
	 */
	private function validate($key, $value)
	{
		$error = '';
		
		switch ($key) 
		{
			case 'name' :				
				if ($value == '')				
					$error = 'Please enter your name or company name.';
				break;
				
			case 'phone_a' :				
				if (!preg_match('#[0-9]{3}#', $value))				
					$error = 'Phone areacode must be 3 numbers.';				
				break;
				
			case 'phone_f' :
				if (!preg_match('#^[0-9]{3}$#', $value))				
					$error = 'Phone first numbers must be 3 numbers.';
				break;
				
			case 'phone_l' :
				if (!preg_match('#^[0-9]{4}$#', $value))				
					$error = 'Phone last numbers must be 4 numbers.';
				break;
				
			case 'fax_a' :
				if (!preg_match('#^[0-9]{3}$#', $value))				
					$error = 'Fax areacode must be 3 numbers.';
				break;
				
			case 'fax_f' :
				if (!preg_match('#^[0-9]{3}$#', $value))				
					$error = 'Fax first numbers must be 3 numbers.';
				break;
				
			case 'fax_l' :
				if (!preg_match('#^[0-9]{4}$#', $value))				
					$error = 'Fax last numbers must be 4 numbers.';
				break;	
			
			case 'street' :
				if (!preg_match('#^[0-9]+.*[a-zA-Z.]+$#', $value))
					$error = 'Street is invalid.';
				break;
			
			case 'apt' :					
				break;
				
			case 'city' :
				if (!preg_match('#^[a-zA-Z -]+$#', $value))
					$error = 'City is invalid.';
				break;				
						
			case 'zip' :
				if (!preg_match('#(^[0-9]{5}$)|(^[0-9]{5}-[0-9]{4}$)#' , $value))
					$error = 'Zip code is invalid.';
				break;
			
			case 'country' :
				if (!preg_match('#^[a-zA-Z ]+$#', $value))
					$error = 'Country is invalid.';
				break;
				
			case 'email' :
				if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#', $value))
					$error = 'Email is invalid.';
				break;
				
			case 'website' :
				if (!preg_match('#^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$#i', $value))
					$error = 'Website is invalid, must include http:// or https://.';
				break;
			
			default :
				break;		
		}
		
		if ($error == '')
			return true;
		
		else 
		{
			$this->error = $error;
			return false;
		}
		
	}
	
// public functions ///////////////////////////////////////////////////////////////////////////// 
	
	/**
	 * @name phone(string $format='area - first - last')	 
	 * @desc Returns the phone number in a format provided.	 
	 * @param string $format : Mix of 'area', 'first', 'last' default: 'area - first - last'.
	 * @return string
	 */
	public function phone($format = 'area - first - last')
	{		
		$phone = str_replace('area', $this->phone_a, 
					str_replace('first', $this->phone_f, 
						str_replace('last', $this->phone_l, $format)));
		
		return $phone;
	}
	
	/**
	 * @name fax(string $format='area - first - last')	 
	 * @desc Returns fax number in a format provided. 
	 * @param string $format : Mix of 'area', 'first', 'last' default: 'area - first - last'.
	 * @return string
	 */
	public function fax($format = 'area - first - last')
	{		
		$fax = str_replace('area', $this->fax_a,
				str_replace('first', $this->fax_f,
					str_replace('last', $this->fax_l, $format)));
		
		return $fax;	
	}
	
	
	/**
	 * @name address(string $format='street apt <br/> city, street zip')	 
	 * @desc Returns address in a format provided. 
	 * @param string $format : Mix of street, apt, city, state, zip, country default: 'street apt &lt;br/&gt; city, street zip'.
	 * @return string
	 */
	public function address($format = 'street apt <br/> city, street zip')
	{
		$address = str_replace('street',$this->street,
					str_replace('apt',$this->apt,
					str_replace('city',$this->city,
					str_replace('state',$this->state,
					str_replace('zip',$this->zip,
					str_replace('country',$this->country, $format))))));
		
		return $address;					
	}
	
	
	/**
	 * @name maplink(string $lan='en')	 
	 * @desc Returns the google maps link.
	 * @param string $lan : language code either 'en' or 'ko'
	 * @return string
	 */
	public function maplink($lan='en')
	{
		switch ($lan)
		{
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
		
		$address = str_replace(' ','+',$this->street.' '.$this->city.', '.$this->state.' '.$this->zip.' '.$this->country);
				
		return $http.'/maps?f=q&source=s_q&hl='.$lan.'&q='.$address.'&z=16';
		
	}
	
	
	/**
	 * @name map(int $width, int $height, string $lan='en')	 
	 * @desc Returns embedded iframe map html data. 
	 * @param int $width : Width of the map size.
	 * @param int $height : Height of the map size.
	 * @param string $lan : Language of the map, 'en' and 'ko' are supported.
	 * @return string
	 */
	public function map($width,$height,$lan='en')
	{
		switch ($lan)
		{
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
		
		$address = str_replace(' ','+',$this->street.' '.$this->city.', '.$this->state.' '.$this->zip);
		
		return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$http.'/maps?f=q&source=s_q&hl='.$lan.'&q='.$address.'&z=15&output=embed"></iframe>';
	}
	
	
	
	/**
	 * @name update(string $key, string $value)
	 * @desc Update contact info from database. Returns true or false.
	 * @param string $key : key of the item to be modified.
	 * @param string $value : value of the key of the item to be modified. 
	 * @return bool
	 */	
	public function update($key, $value)
	{		
		$key;
		$value = Won::get('DB')->sql->real_escape_string(htmlspecialchars(trim($value)));
		
		if ($this->validate($key, $value))
		{		
			Won::get('DB')->sql->query("			
				UPDATE `{$this->table}`
				SET		`{$key}` = '{$value}'			
			");
			return true;
		}
		
		else
			return false;
	}
		
		
	/**
	 * @name update_all(string $name, string $phone_areacode, string $phone_first, string $phone_last, string $fax_areacode, string $fax_first, string $fax_last, string $street, string $apt, string $city, string $state, string $country, string $zip, string $email, string $website)
	 * @desc Update contact info from database. Does not validate the values.
	 * @param string $name : Name.
	 * @param string $phone_areacode : phone_areacode
	 * @param string $phone_first : phone_first
	 * @param string $phone_last : phone_last
	 * @param string $fax_areacode : fax_areacode
	 * @param string $fax_first : fax_first
	 * @param string $fax_last : fax_last
	 * @param string $street : street
	 * @param string $apt : apt
	 * @param string $city : city
	 * @param string $state : state
	 * @param string $country : country
	 * @param string $zip : zip
	 * @param string $email : email
	 * @param string $website : website
	 * @return void
	 */	
	public function update_all($name, $phone_areacode, $phone_first, $phone_last, $fax_areacode, $fax_first, $fax_last, $street, $apt, $city, $state, $country, $zip, $email, $website)
	{
		Won::get('DB')->sql->query("
		
			UPDATE `{$this->table}`
			SET		`name` = '{$name}',
					`phone_a` = '{$phone_areacode}',
					`phone_f` = '{$phone_first}',
					`phone_l` = '{$phone_last}',
					`fax_a` = '{$fax_areacode}',
					`fax_f` = '{$fax_first}',
					`fax_l` = '{$fax_last},
					`street` = '{$street}',
					`apt` = '{$apt}',
					`city` = '{$city}',
					`state` = '{$state}',
					`country` = '{$country}',
					`zip` = '{$zip}',
					`email` = '{$email}',
					`website` = '{$website}'					
		
		") or die(Won::get('DB')->sql->error);
		
		$this->refresh_list();
	}
	
	
	/**
	 * @name refresh_list()	 
	 * @desc Update this class's properties, reading directly from the database.
	 * @param none
	 * @return void
	 */
	private function refresh_list()
	{
		$contacts = Won::get('DB')->sql->query("
			
			SELECT `name`,`phone_a`,`phone_f`,`phone_l`,`fax_a`,`fax_f`,`fax_l`,`street`,`apt`,`city`,`state`,`country`,`zip`,`email`,`website` 
			FROM `{$this->table}`	
		
		") or die (Won::get('DB')->sql->error);
				
		if ($contacts->num_rows) {
			$contact = $contacts->fetch_assoc();	
			foreach ($contact as $key=>$value)
				$this->$key = $value;								
		}
		
	}
	
	
}
?>