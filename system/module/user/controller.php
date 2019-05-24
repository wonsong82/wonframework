<?php
namespace app\module;
final class UserController extends \app\engine\Controller{
	
	public $logged; // bool is user logged
	public $active; // bool is user active
	public $banned; // bool is user banned
	public $joined; // time when user joined
	public $username; // username of the user
	public $isAdmin;
		
	private $sessionName;
	private $encodedSessionName;
	private $encodedIsAdmin;
	private $phpassObj;
	private $codeObj;
		
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		
		// Import Packages
		$this->phpassObj = $this->loader->getClass('auth.PHPass');
		$this->codeObj = $this->loader->getClass('auth.Code');
		
		// $sessionName is what client will use for their session and form
		$this->sessionName = 'webwon_user';
		$this->encodedSessionName = $this->codeObj->encrypt($this->sessionName);	
		$this->encodedIsAdmin = $this->codeObj->encrypt('isadmin');
		
		$this->isAdmin = false;
		$this->logged = false;	
	}
	
	// @override
	// Add more stuffs when updating DB
	public function updateDB(){
		parent::updateDB();
		$usermeta=$this->model->query("
			SELECT [usermeta.id] AS [id] FROM [usermeta]
		");
		if(!count($usermeta)){
			$this->model->query("
				INSERT INTO [usermeta] SET [usermeta.default_group]=1
			");
		}
	}


	//
	// Authenticate the user authority	
	public function auth(){
		// Session Check
		if(isset($this->session->data[$this->encodedSessionName]) && !empty($this->session->data[$this->encodedSessionName])){
			$encryptedUserID = $this->session->data[$this->encodedSessionName];
			$userID = $this->codeObj->decrypt($encryptedUserID);
			$authMethod = 'session';
		}
		// Cookie Check
		elseif(isset($this->req->cookie[$this->sessionName])){
			$encryptedUserID = $this->req->cookie[$this->sessionName];
			$userID = $this->codeObj->decrypt($encryptedUserID);
			$authMethod = 'cookie';
		}
		// Post Check
		elseif(isset($this->req->post[$this->sessionName.'_id']) && !empty($this->req->post[$this->sessionName.'_id']) && isset($this->req->post[$this->sessionName.'_pass']) && !empty($this->req->post[$this->sessionName.'_pass'])){
			$username = trim($this->req->post[$this->sessionName.'_id']);
			$password = trim($this->req->post[$this->sessionName.'_pass']);
			$userID = $this->checkUserPass($username, $password);
			if(false===$userID){
				$this->error = 'Invalid Authentication';
				return false;
			}
			// If good
			$encryptedUserID = $this->codeObj->encrypt($userID);
			$authMethod = 'post';
		}
		
		// If None
		else{
			$this->error = 'Invalid User';
			return false;
		}
		
		// Auth completed, get user info
		$user = $this->getUser($userID);
		if(!$user)
			return false;
		
		// Set parameters to this object
		foreach($user as $f=>$v) $this->$f = $v;
		
		// Validate
		if(!$this->active){
			$this->error = 'User is Inactive';
			return false;
		}
		if($this->banned){
			$this->error = 'User is Banned';
			return false;
		}
		
		// All Good
		$this->logged = true;
		$this->session->data[$this->encodedSessionName] = $encryptedUserID;
		if($authMethod=='cookie' || 
			($authMethod=='post'&&isset($this->req->post[$this->sessionName.'_remember'])&&$this->req->post[$this->sessionName.'_remember']))
			setcookie($this->sessionName, $encryptedUserID, time()+60*60*24*7);
		if($this->isMemberOf('Administrator'))
			$this->isAdmin = true;
		return true;		
	}
	
	//
	// Log out of the user
	public function logout(){
		if(isset($this->session->data[$this->encodedSessionName])){
			unset($this->session->data[$this->encodedSessionName]);
			setcookie($this->sessionName,'',time()-3600);
		}
		return true;
	}
	
	


// USER RELATED /////////////////////////////////////////////////	
	//
	// Get user information by userid or username
	public function getUser($param){
		// Set Where Causes
		if((int)$param > 0){
			$userid = $this->db->escape((int)$param);
			$where = "[user.id] = {$userid}";
		} else {
			$username = $this->db->escape($param);
			$where = "[user.username] = '{$username}'";
		}
		// get from db
		$result = $this->model->query("
			SELECT  [user.id] AS [id], [user.username] AS [username],
					[user.active] AS [active], [user.banned] AS [banned],
					[user.joined] AS [joined],
					(
						SELECT GROUP_CONCAT([usergroup.name] SEPARATOR '-sep')
						FROM [usergroup], [usermembership]
						WHERE [usergroup.id]=[usermembership.groupid]
						AND [usermembership.userid]=[user.id]
					) AS [groups]
			FROM [user]
			WHERE {$where}
		");
		if(count($result)){
			$user = $result[0];
			$user['id'] = (int)$user['id'];
			$user['active'] = (bool)$user['active'];
			$user['banned'] = (bool)$user['banned'];
			$user['groups'] = explode('-sep', $user['groups']);
			return $user;
		}
		else{
			$this->error = 'Invalid User';			
			return false;
		}
	}
	
	//
	// Get All Users
	public function getUsers(){
		// get from db
		/*
		$users = $this->model->query("
			SELECT  [user.id] AS [id], [user.username] AS [username],
					[user.active] AS [active], [user.banned] AS [banned],
					[user.joined] AS [joined],
					(
						SELECT GROUP_CONCAT([usergroup.name] SEPARATOR '-sep')
						FROM [usergroup], [usermembership]
						WHERE [usergroup.id]=[usermembership.groupid]
						AND [usermembership.userid]=[user.id]
					) AS [groups]
			FROM [user]
			ORDER BY [user.id]
		");
		foreach($users as &$user){
			$user['id'] = (int)$user['id'];
			$user['active'] = (bool)$user['active'];
			$user['banned'] = (bool)$user['banned'];
			$user['groups'] = explode('-sep', $user['groups']);
		}*/
		$users = $this->model->query("
			SELECT [user.id] AS [id], [user.username] AS [username]
			FROM [user]
			ORDER BY [order]
		");
		return $users;
	}
	
	//
	// Add a User
	public function addUser($username, $password, $active=true, $banned=false){
		// Validate
		if(!$this->model->field('user.username')->validate($username)){
			$this->error = 'Invalid Username Format';
			return false;
		}
		if(!$this->model->field('user.password')->validate($password)){
			$this->error = 'Invalid Password Format';
			return false;
		}
		// Clean
		$username = $this->db->escape($username);
		$password = $this->phpassObj->getHash($password);
		$active = (int)$active;
		$banned = (int)$banned;
		$joined = date('Y-m-d H:i:s');
		// Check for duplicates
		if(false!==$this->getUser($username)){
			$this->error = 'Duplicated Entree';
			return false;
		}
		// Add to DB
		$nextOrder = $this->nextOrder('user');
		$this->model->query("
			INSERT INTO [user]
			SET [user.username] = '{$username}',
				[user.password] = '{$password}',
				[user.active] = {$active},
				[user.banned] = {$banned},
				[user.joined] = '{$joined}',	
				[order]	= {$nextOrder}	
		");
		
		$this->addUserToGroup($username);
		return true;
	}
	
	//
	// Remove a user by ID
	public function removeUser($id){
		$id = (int)$this->db->escape($id);
		$this->model->query("
			DELETE FROM [user]
			WHERE [user.id] = {$id}
		");
		$this->model->query("
			DELETE FROM [usermembership]
			WHERE [usermembership.userid] = {$id}
		");
	}
		
	//
	// Check for group membership
	public function isMemberOf($groupName){
		if(!$this->logged){
			$this->error = 'Invalid Authentication';
			return false;
		}
		if(in_array($groupName, $this->groups))
			return true;
		else{
			$this->error = 'User is Unauthorized';
			return false;
		}
	}
	
	public function isUserMemberOf($user, $group){
		$user = $this->getUser($user);
		if(false===$user){
			$this->error = 'Invalid User';
			return false;
		}
		$userID = (int)$user['id'];
		$group = $this->getGroup($group);
		if(false===$group){
			$this->error = 'Invalid Group';
			return false;
		}
		$groupID = (int)$group['id'];
		$query=$this->model->query("
			SELECT [usermembership.id] AS [id]
			FROM [usermembership]
			WHERE [usermembership.userid]={$userID} 
			AND [usermembership.groupid]={$groupID}
		");
		if(false===$query){
			$this->error = $this->db->lastError();
			return false;
		}
		return count($query)? 1:0;
	}
	
// GROUP RELATED ////////////////////////////////////////////////

	public function getGroup($param){
		// Set Where Causes
		if((int)$param > 0){
			$id = $this->db->escape((int)$param);
			$where = "[usergroup.id] = {$id}";
		} else {
			$name = $this->db->escape($param);
			$where = "[usergroup.name] = '{$name}'";
		}
		$group = $this->model->query("
			SELECT 	[usergroup.id] AS [id], 
					[usergroup.name] AS [name], 
					[usergroup.editable] AS [editable]
			FROM	[usergroup]
			WHERE	{$where}
		");
		$group[0]['id']=(int)$group[0]['id'];
		$group[0]['editable']=(bool)(int)$group[0]['editable'];
		return $group[0];
	}
	
	public function getGroups(){
		return $this->model->query("
			SELECT 	[usergroup.id] AS [id], 
					[usergroup.name] AS [name]
			FROM	[usergroup]
			ORDER BY [order]
		");
	}
	
	public function addGroup($groupName, $editable=true){
		// Validate
		if(!$this->model->field('usergroup.name')->validate($groupName)){
			$this->error = 'Format is Invalid';
			return false;
		}
		// Escape
		$groupName = $this->db->escape($groupName);
		$editable = (int)$editable;
		// Exist Check
		$exist = $this->model->query("
			SELECT [usergroup.id] AS [id] FROM [usergroup]
			WHERE [usergroup.name] = '{$groupName}'
		");
		if(count($exist)){
			$this->error = 'Duplicated Entree';
			return false;
		}
		
		// Add
		$nextOrder = $this->nextOrder('usergroup');
		$result=$this->model->query("
			INSERT INTO [usergroup]
			SET		[usergroup.name] = '{$groupName}',
					[usergroup.editable] = {$editable},
					[order] = {$nextOrder}
		");
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;			
	}
	
	public function removeGroup($id){
		$id = (int)$this->db->escape($id);
		$this->model->query("
			DELETE FROM [usergroup]
			WHERE [usergroup.id] = {$id}
		");
	}
	
	public function setDefaultGroup($group){
		$group = $this->getGroup($group);
		if(false===$group){
			$this->error = 'Invalid Data';
			return false;
		}
		$groupID = (int)$group['id'];
		$this->model->query("
			UPDATE [usermeta]
			SET [usermeta.default_group]={$groupID}
		");		
		return true;
	}
	
	public function updateGroup($userID, $groupID, $bool){
		$bool=(int)$bool;
		if($bool)
			$this->addUserToGroup($userID, $groupID);
		else
			$this->removeUserFromGroup($userID, $groupID);
	}
	
	public function addUserToGroup($user, $group=null){
		$user = $this->getUser($user);
		if(false===$user){
			$this->error = 'Invalid Data';
			return false;
		}
		$userID = (int)$user['id'];
						
		if(is_null($group)){
			$group = $this->model->query("
				SELECT [usermeta.default_group] AS [default] FROM [usermeta]
				LIMIT 1
			");
			if(!count($group)){
				$this->error = 'Invalid Data';
				return false;
			}
			$groupID = (int)$group[0]['default'];						
		} else {
			$group = $this->getGroup($group);
			if(false===$group){
				$this->error = 'Invalid Data';
				return false;
			}
			$groupID = (int)$group['id'];
		}
		
		$exist = $this->model->query("
			SELECT [usermembership.id] AS [id]
			FROM [usermembership]
			WHERE [usermembership.userid] = {$userID}
			AND [usermembership.groupid] = {$groupID}
		");	
		if(count($exist)){
			$this->error = 'Duplicated Entree';
			return false;
		}
		$this->model->query("
			INSERT INTO [usermembership]
			SET	[usermembership.userid] = {$userID},
				[usermembership.groupid] = {$groupID}
		");
		return true;
	}
	
	public function removeUserFromGroup($user, $group){
		$user = $this->getUser($user);
		if(false===$user){
			$this->error = 'Invalid User';
			return false;
		}
		$userID = (int)$user['id'];
		$group = $this->getGroup($group);
		if(false===$group){
			$this->error = 'Invalid Group';
			return false;
		}
		$groupID = (int)$group['id'];
		
		$query=$this->model->query("
			DELETE FROM [usermembership]
			WHERE [usermembership.userid] = {$userID}
			AND [usermembership.groupid] = {$groupID}
		");
		if(false===$query){
			$this->error = 'Problem Occured';
			return false;
		}
		return true;
	}
	
	
	
	
///////////////////////////////////////////////////////////////////////////////////	
	// @override (cuz of the password)
	// Update Individual Fields
	public function update($field, $id, $val){
		// Validate
		if(!$this->model->field($field)->validate($val)){
			$this->error = ucwords('invalid '.str_replace('.',' ',$field).' format');
			return false;
		}
		// values
		$id = (int)$this->db->escape($id);
		if(is_bool($val)) $val = (int)$val;
		$val = $field=='user.password'?
			$this->phpassObj->getHash($val) :
			$this->db->escape($val);		
		// Update
		$table = preg_replace('#\.[a-zA-Z]+$#', '', $field);
		$this->model->query("
			UPDATE [{$table}]
			SET [{$field}] = '{$val}'
			WHERE [{$table}.id] = {$id}
		");	
		return true;	
	}
	
	
	private function checkUserPass($username, $password){
		$username = $this->db->escape($username);
		$password = $this->db->escape($password);
		$result = $this->model->query("
			SELECT [user.id] AS [id], [user.password] AS [password]
			FROM [user]
			WHERE UPPER([user.username]) = UPPER('{$username}')
		");
		if(count($result)){
			return $this->phpassObj->checkHash($password,$result[0]['password'])?
				$result[0]['id'] : false;
		} 
		else
			return false;		
	}
	
	
	
	
}
?>