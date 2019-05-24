<?php
// namespace com\won\server;
final class com_won_server_RegisterGlobalsFix {
	
	public function __construct() {
		if (ini_get('register_globals')) {
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
				
			session_set_cookie_params(0, '/');
			session_start();
			
			$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);
		
			foreach ($globals as $global) {
				foreach(array_keys($global) as $key) {
					unset(${$key}); 
				}
			}
		}
	}
}
?>