<?php
// Name : SimpleAuth
// Desc : Simple ID and Password Authorizer

//namespace com\won\auth;
class com_won_auth_SimpleAuth{
	
	private $sessionName = null;
	private $sessionNameEncoded = null;
	private $auths = array(); 
	private $phpass = null;
	
	public $error = false;
	public $errorMsg = '';
	public $lastID = '';
	
	public function __construct($sessionName){		
		
		require_once dirname(__FILE__).'/PHPass.php';
		$this->phpass = new com_won_auth_PHPass();
						
		// $sessionName is what Client will user for their session and form
		$this->sessionName = $sessionName;
		$this->sessionNameEncoded = md5($sessionName);		
	}
	
	public function addUser($userID, $password){
	// We dont need MD5 cuz password is not transfered
		$this->auths[] = array(
			'userID' => $userID,
			'password' => $password
		);
	}
	
	public function auth(){
		
		// Check Session
		if(isset($_SESSION[$this->sessionNameEncoded]) && $_SESSION[$this->sessionNameEncoded]==1){
			return true;
		}
		
		// Check Post
		elseif(isset($_POST[$this->sessionName.'_userID'])){
			$userID = trim($_POST[$this->sessionName.'_userID']);
			$password = trim($_POST[$this->sessionName.'_password']);
			$this->lastID = $userID;
			foreach($this->auths as $account){
				if($userID == $account['userID'] && $this->phpass->checkHash($password, $account['password'])){
					$_SESSION[$this->sessionNameEncoded]=1;
					return true;				
				}
				$this->error = true;
				$this->errorMsg = 'Incorrect UserID or Password';
				return false;
			}
		}
		// Return Error
		else{
			return false;
		}
	}
	
	public function logout(){
		if(isset($_SESSION[$this->sessionNameEncoded]))
			unset($_SESSION[$this->sessionNameEncoded]);
	}
	
}
?>