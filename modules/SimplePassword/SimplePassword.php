<?php
class SimplePassword
{
	private static $prefix = 'webwon_simple_password';
	
	public static function set($session_name, $password, $md5=false)
	{
		$name = SimplePassword::$prefix . '_' . $session_name;
		$pass = $md5? $password : md5($password);
		
		if (!SimplePassword::check_session($name))	
		{
			$post = SimplePassword::check_post($pass);
			
			if ($post === true)			
				$_SESSION[$name] = '1';			
			
			else			
				SimplePassword::display_login_form($post);			
		}	
		
	}
	
	public static function logout($session_name)
	{
		$name = SimplePassword::$prefix . '_' . $session_name;
		
		if (isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}
	
	
	// check session. if the $session_name is there, return true, else false.
	private static function check_session($session_name)
	{	
		return isset($_SESSION[$session_name]) && $_SESSION[$session_name] == '1' ? true : false;	
	}
	
	// check post. if the post if $_POST is set and password is correct, return true , else false.
	private static function check_post($password)
	{
		if (isset($_POST[SimplePassword::$prefix]))
		{
			 if ($password == md5(trim($_POST[SimplePassword::$prefix])))
			 	return true;
			 else
			 	return 'error';
		}
		
		else
		{
			return false;
		}
		
		
	}
	
	// if not logged, display the login form and exit the program.
	private static function display_login_form($error)
	{		
		echo '<form action="'. $_SERVER['PHP_SELF'] .'" method="post">' .
				'Password : <input name="'.SimplePassword::$prefix.'" type="password" />' .
			'</form>';
		echo '<script>document.forms[0].'.SimplePassword::$prefix.'.focus();</script>';
		echo $error=='error'? '<p style="color:#f00">Wrong password. Try again.</p>' : '';
		exit();
	}
}


?>