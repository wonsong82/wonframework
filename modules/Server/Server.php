<?php
// Server information

class Server
{
	public function __construct()
	{
	}
	
	// phpinfo
	public function info()
	{
		return phpinfo();
	}
	
	
	// check if database is working for given information
	public function check_database($host, $user, $pass, $db)
	{
		if ($host=='') 		
			return false;
				
		@$sql = new MySQLi($host, $user, $pass, $db);		
		if ($sql->connect_error)
			return false;		
		
		$checkdb = $sql->query("
			SELECT `SCHEMA_NAME` FROM INFORMATION_SCHEMA.SCHEMATA
			WHERE SCHEMA_NAME = '{$db}'
		");
		if (!$checkdb->num_rows)
			return false;			
		
		return true;
	}
	
	
	// check php version requirement
	// $return['status']
	//        ['data'][0]['name']
	//                   ['status']
	//                   ['error_msg']
	public function check_php_version()
	{
		$req_version = 5.0;
		
		$php_ver = array();
		preg_match('#[0-9]+\.[0-9]+#', phpversion(), $php_ver);
		$php_ver = $php_ver[0];
				
		$result = array();
		$result['status'] = $php_ver >= $req_version? true : false;	
		$result['data'] = array();			
		$result['data'][] = array(
							'name' => 'php_version',
							'status' => $php_ver >= $req_version? true : false,
							'error_msg' => $php_ver >= $req_version? '' : 'Require PHP 5.0 or higher.'
		);
		
		return $result;
	}
	
	
	// check mysql version requirement
	// $return['status']
	//        ['data'][0]['name']
	//                   ['status']
	//                   ['error_msg']
	public function check_mysql_version()
	{
		$req_version = 5.0;
		
		$mysql_ver = array();
		preg_match('#[0-9]+\.[0-9]+#', mysql_get_client_info(), $mysql_ver);
		$mysql_ver = $mysql_ver[0];	
		
		$result = array();
		$result['data'] = array();		
		
		$result['status'] = $mysql_ver >= $req_version? true : false;
		$result['data'][] = array(
							'name' => 'mysql_version',
							'status' => $mysql_ver >= $req_version? true : false,
							'error_msg' => $mysql_ver >= $req_version? '' : 'Require Mysql 5.0 or higher.'
		);
		
		return $result;
	}
	
	
	// check apache requirements
	// $return['status']
	//        ['data'][0]['name']
	//                   ['status']
	//                   ['error_msg']
	public function check_apache()
	{
		// list to check
		$list = array(
		
			'Rewrite Mod' => 'HTTP_MOD_REWRITE'
		
		);			
		
		
		$no_error = true;		
		$result = array();		
		$result['data'] = array();
		
		foreach ($list as $mod_name => $mod_key)
		{
			$mod_on = getenv($mod_key) =='On' ? true : false;
			
			if (!$mod_on)
				$no_error = false;
						
			$result['data'][] = array(
								'name' => $mod_name,
								'status' => $mod_on,
								'error_msg' => $mod_name . ' must be turned on.'
			);
		}
		
		$result['status'] = $no_error;
		
		return $result;
	}
	
	
	
		
	
	// check apache requirements
	// $return['status']
	//        ['data'][0]['name']
	//                   ['status']
	//                   ['error_msg']
	public function check_php()
	{
		// check settings
		$settings = array(
		
			'short_open_tag',
			'file_uploads'		
		);
		
		$no_error = true;		
		$result = array();		
		$result['data'] = array();			
		
		foreach ($settings as $setting)
		{
			$mod_on = ini_get($setting)==1 ? true : false;
			
			if (!$mod_on)
				$no_error = false;
			
			$result['data'][] = array(
									'name' => $setting,
									'status' => $mod_on,
									'error_msg' => $setting . ' must be turned on.'
			);
		}
		
		$result['status'] = $no_error;
		
		
		// check modules
		$extensions = array(
		
			'curl',
			'gd',
			'mbstring',
			'mysql',
			'mysqli'	
		
		);
		
		foreach ($extensions as $extension)
		{
			$mod_on = extension_loaded($extension);
						
			if (!$mod_on)
				$no_error = false;
			
			$result['data'][] = array(
									'name' => $extension,
									'status' => $mod_on,
									'error_msg' => $extension . ' must be turned on.'
			);
		}
		
		$retuls['status'] = $no_error;
		
		return $result;
	}
	
		
	
}

?>