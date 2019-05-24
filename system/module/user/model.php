<?php
class UserModel extends Model {
	
	// Initialize Data Structure
	protected function init() {
		
		// User Table
		$this->setField('user.username', 'username', 'VARCHAR(255)', '#^[a-zA-Z0-9]{5,20}#', 'UNIQUE');
		$this->setField('user.password', 'password', 'VARCHAR(255)', '#^[a-zA-Z0-9\_\-\/\?\!\-\_]{3,}$#');
		$this->setField('user.active', 'active', 'INT(1) DEFAULT 1');
		$this->setField('user.banned', 'banned', 'INT(1) DEFAULT 0');
		$this->setField('user.joined', 'joined', 'DATETIME');
		
		// Group Table
		$this->setField('group.name' , 'name', 'VARCHAR(255)', null, 'INDEX');
		$this->setField('group.editable', 'editable', 'INT(1) DEFAULT 1');
		$this->setField('group.sortOrder', 'sort_order', 'BIGINT', null, 'INDEX');
		
		// Group Membership Table
		$this->setField('membership.groupID', 'group_id', 'BIGINT', 'INDEX');
		$this->setField('membership.userID', 'user_id', 'BIGINT', 'INDEX');		
		
		// Errors
		$this->setError('INVALID_USERNAME', 'Username must be combination of alphabets and numbers, between 5 to 20 characters long.');
		$this->setError('INVALID_PASSWORD', 'Password must contain no special characters, at least 3 characters long');
		$this->setError('INACTIVE_USER', 'Your account is inactive.');
		$this->setError('BANNED_USER', 'Your account is banned.');
		$this->setError('INVALID_USER', 'User is unavailable.');
		$this->setError('MISMATCH_USER', 'Incorrect username or password.');
		$this->setError('INVALID_AUTH', 'Cannot authenticate user.');
		$this->setError('NOT_A_GROUP_MEMBER', 'You are not a member of {$group} group.');
		
	}
	
	public function checkUsernamePassword($username, $password) {
		
		$username = $this->db->escape($username);
		$password = md5($this->db->escape($password));
		
		$userTable = $this->table['user']['name'];
		$usernameField = $this->table['user']['fields']['username']['name'];
		$passwordField = $this->table['user']['fields']['password']['name'];
		
		$userID = $this->db->query("
			SELECT `id` FROM `{$userTable}`
			WHERE UPPER(`{$usernameField}`) = UPPER('{$username}')
			AND   `{$passwordField}` = '{$password}' 
		");
		
		return $this->db->numRows()? $userID[0]['id'] : false;
	}
	
	public function getUserByID($userID) {
		
		$userID = (int) $this->db->escape($userID);
		$userTable = $this->table['user']['name'];
		$groupTable = $this->table['group']['name'];
		$groupName = $this->table['group']['fields']['name']['name'];
		$membershipTable = $this->table['membership']['name'];
		$groupID = $this->table['membership']['fields']['groupID']['name'];
		$groupUserID = $this->table['membership']['fields']['userID']['name'];
		$userFields = $this->getFields('user');		
		
		$user = $this->db->query("
			SELECT 	{$userFields}, (
				SELECT GROUP_CONCAT(`g`.`{$groupName}` SEPARATOR '-sep-')
				FROM `{$groupTable}` `g`, `{$membershipTable}` `gm`
				WHERE `g`.`id` = `gm`.`{$groupID}` AND `gm`.`{$groupUserID}` = `u`.`id`
			) AS `groups`		
			FROM `{$userTable}` `u`
			WHERE `u`.`id` = {$userID}
		");
		
		return $this->db->numRows()? $user[0] : false;
	}
	
	public function getUsers() {
		
		$userTable = $this->table['user']['name'];
		$groupTable = $this->table['group']['name'];
		$membershipTable = $this->table['membership']['name'];
		$userFields = $this->getFields('user');
		$groupName = $this->table['group']['fields']['name']['name'];
		$groupID = $this->table['membership']['fields']['groupID']['name'];
		$groupUserID = $this->table['membership']['fields']['userID']['name'];
		
		return $this->db->query("
			SELECT {$userFields}, (
				SELECT GROUP_CONCAT(`g`.`{$groupName}` SEPARATOR '-sep-')
				FROM `{$groupTable}` `g`, `{$membershipTable}` `gm`
				WHERE `g`.`id` = `gm`.`{$groupID}` AND `gm`.`{$groupUserID}` = `u`.`id`
			) AS `groups`		
			FROM `{$userTable}` `u`
			ORDER BY `id`
		");
	}
	
	public function addUser($args) {
		
		$userTable = $this->table['user']['name'];
		foreach ($this->table['user']['fields'] as $field) {
						
		}
		
		
		
		$userName = $this->db->escape($username);
		$password = md5($this->db->escape($password));
		$active = (int)$active;
		$banned = (int)$banned;
		
		$userTable = $this->table['user']['name'];
		
		$this->db->query("
			INSERT INTO `{$userTable}`
						
		");
	}
}
?>