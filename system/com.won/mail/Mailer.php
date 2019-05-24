<?php
// namespace com\won\mail;
final class com_won_mail_Mailer{
	
	private $phpMailer;
	
	public function __construct(){
		require_once dirname(__FILE__).'/phpmailer/class.phpmailer.php';
		$this->phpMailer = new PHPMailer();
	}
	
	public function __call($func, $args){
		return call_user_func_array(array($this->phpMailer, ucfirst($func)), $args);
	}
	
	public function __get($key){
		$key = ucfirst($key);
		return $this->phpMailer->$key;
	}
	
	public function __set($key, $val){
		$key = ucfirst($key);
		$this->phpMailer->$key = $val;
	}
	
}
?>