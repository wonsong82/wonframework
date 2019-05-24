<?php
class UserController extends Controller {
	
	private $prefix = 'webwon_user_auth_';
	public $logged = false;
	public $active;
	public $banned;
	public $joined;
	public $username;
	
	protected function init() {
		
		// Import Declarations
		$this->lib->import('util.code');
		
		
		//$this->session->data['webwon_user_auth_userID'] = 'c4ca4238a0b92382TVE9PQ==0dcc509a6f75849b';
		
		//$this->auth();
		//$this->isMember('Administrator');
		//var_dump($this->getUsers());
		
	}
	
	
// Current USER Related Controllers ////////////////////////////////////////	
	
	protected function auth() {		
		
		// Session Check
		if (isset($this->session->data[$this->prefix.'userID']) && !empty($this->session->data[$this->prefix.'userID'])) {
			$encryptedUserID = $this->session->data[$this->prefix.'userID'];
			$userID = $this->lib->code->decrypt($encryptedUserID);
			$authMethod = 'session';
		}		
		// Cookie Check
		elseif (isset($this->request->cookie[$this->prefix.'userID'])) {
			$encryptedUserID = $this->request->cookie[$this->prefix.'userID'];
			$userID = $this->lib->code->decrypt($encryptedUserID);
			$authMethod = 'cookie';	
		}		
		// Post Check
		elseif (isset($this->request->post[$this->prefix.'username']) && !empty($this->requeset->post[$this->prefix.'username']) && isset($this->request->post[$this->prefix.'password']) && !empty($this->request->post[$this->prefix.'password'])) {
			$username = $this->request->post[$this->prefix.'username'];
			$password = $this->request->post[$this->prefix.'password'];
			$userID = $this->model->checkUsernamePassword($username, $password);
			if (!$userID) {
				$this->setError('MISMATCH_USER');
				return false;
			}
			$encryptedUserID = $this->lib->code->encrypt($userID);
			$authMethod = 'post';
		}		
		// If None
		else {
			$this->setError('INVALID_AUTH');
			return false;
		}		
		// Get User Info
		$user = $this->getUserByID($userID);
		if (!$user) {
			$this->setError('INVALID_USER');
			return false;
		}
		foreach ($user as $f=>$v)
			$this->$f = $v;
		if (!$this->active) {
			$this->setError('INACTIVE_USER');
			return false;
		}
		if ($this->banned) {
			$this->setError('BANNED_USER');
			return false;
		}
		$this->logged = true;
		$this->session->data[$this->prefix.'userID'] = $encryptedUserID;
		if ($authMethod=='cookie' || ($authMethod=='post'&&isset($this->request->post[$this->prefix.'remember'])&&$this->request->post[$this->prefix.'remember']))
			setcookie($this->prefix.'userID', $encryptedUserID, time()+60*60*24*7);
		return true;								
	}
	
	
	// Log Off the user (reset session and cookie)
	public function logout() {
		
		if (isset($this->session->data[$this->prefix.'userID'])) {
			unset($this->session->data[$this->prefix.'userID']);
			setcookie($this->prefix.'userID', '', time()-3600);
		}
		return true;
	}
	
	// Check for group membership
	public function isMember($groupName) {
		
		if (!$this->logged)
			return false;
		if (in_array($groupName, $this->groups)) 
			return true;
		else {
			$this->setError('NOT_A_GROUP_MEMBER', $groupName);
			return false;
		}
	}
	
	
// User Related Controllers ////////////////////////////////////////////////////////
	
	// Get A Single User By Provided ID(Index)
	public function getUserByID($userID) {
		
		if ($user = $this->model->getUserByID($userID)) {
			$user['groups'] = explode('-sep', $user['groups']);
			return $user;
		}
		else 
			return false;	
	}
	
	// Get All Users
	public function getUsers() {
		
		$users = $this->model->getUsers();
		for ($i=0; $i<count($users); $i++) {
			$users[$i]['groups'] = explode('-sep', $users[$i]['groups']);
		}
		return $users;
	}
	
	// Add User
	public function addUser($args) {
		
		if (!$this->model->addUser($args)) {
			$this->setError($this->model->errorCode);
			return false;
		}
		return true;
	}
	
	
	
	
}
?>