<?php
// ModuleNavigationSortOrder=2;
class User extends WonClass
{
	public $id;
	public $logged = false;
	
	public $username;
	public $name;
	public $email;
	
	public $active;
	public $banned;
	public $created_time;
	public $last_login;
		
	public $groups = array();
	
	private $user_table;
	private $group_table;
	private $group_membership_table;
	private $log_table;
	
	private $prefix = 'webwon_user_auth_';
	
	
	protected function init()
	{
		// set prefix
		$this->user_table = Won::get('DB')->prefix . 'user';
		$this->group_table = Won::get('DB')->prefix . 'user_group';
		$this->group_membership_table = Won::get('DB')->prefix . 'user_group_membership';
		$this->log_table = Won::get('DB')->prefix . 'user_log';
		
		// create user table
		Won::get('DB')->sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->user_table}` (
				`id` SERIAL NOT NULL,
				`username` VARCHAR(255) NOT NULL,
				`password` VARCHAR(255) NOT NULL,
				`name` VARCHAR(255) NOT NULL,
				`email` VARCHAR(255) NOT NULL,
				`active` INT(1) DEFAULT 1,
				`banned` INT(1) DEFAULT 0,
				`created_time` DATETIME NOT NULL,			
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`					
		") or die(Won::get('DB')->sql->error);		
		
		// create group table
		Won::get('DB')->sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->group_table}` (
				`id` SERIAL NOT NULL,
				`name` VARCHAR(255) NOT NULL,
				`editable` INT(1) DEFAULT 1,
				`sort_order` BIGINT NOT NULL,
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`					
		") or die(Won::get('DB')->sql->error);	
		
		// create group_membership table
		Won::get('DB')->sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->group_membership_table}` (
				`id` SERIAL NOT NULL,
				`group_id` BIGINT NOT NULL,
				`user_id` BIGINT NOT NULL,
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`					
		") or die(Won::get('DB')->sql->error);	
		
		// create 
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->log_table}` (
				`id` SERIAL NOT NULL,
				`user_id` BIGINT NOT NULL,
				`action` VARCHAR(255),
				`value` VARCHAR(255),
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);
	}
	
	public function authenticate()
	{
		if (isset($_SESSION[$this->prefix . 'id']) && intval($_SESSION[$this->prefix.'id']) >0)
			$this->auth_session();
		
		elseif(isset($_POST[$this->prefix.'user']) && $_POST[$this->prefix.'user']!='' &&
			   isset($_POST[$this->prefix.'pass']) && $_POST[$this->prefix.'pass']!='')
			$this->auth_post();					
	}
	
	private function auth_session()
	{
		$user_id = $_SESSION[$this->prefix . 'id'];
		if ($user = $this->get_user_by_id($user_id))
		{
			$this->id = $user['id'];
			$this->username = $user['username'];
			$this->name = $user['name'];
			$this->email = $user['email'];
			$this->active = (bool)$user['active'];
			$this->banned = (bool)$user['banned'];
			$this->created_time = $user['created_time'];
			$this->groups = $user['groups'];
			
			$curtime = date('Y-m-d H:i:s');
			$this->write_log('login', $curtime);
			
			$this->last_login = $user['last_login'];
			
			if (!$this->active)
				$this->error = 'Inactivated User';
			
			else if ($this->banned)
				$this->error = 'Banned User'; 
			
			else
				$this->logged = true;			
		}
		
		else
		{
			$this->error = 'Invalid Session.';
		}
	}
	
	private function auth_post()
	{
		$username = $_POST[$this->prefix.'user'];
		$password = md5($_POST[$this->prefix.'pass']);
		
		$auth = Won::get('DB')->sql->query("
			SELECT COUNT(id) FROM {$this->user_table}
			WHERE username = '{$username}' AND password = '{$password}'
		") or die(Won::get('DB')->sql->error);
		$auth = $auth->fetch_row();
		$auth = $auth[0];
		
		if ($auth > 0)
		{
			if ($user = $this->get_user_by_username($username))
			{
				$this->id = $user['id'];
				$this->username = $user['username'];
				$this->name = $user['name'];
				$this->email = $user['email'];
				$this->active = (bool)$user['active'];
				$this->banned = (bool)$user['banned'];
				$this->created_time = $user['created_time'];
				$this->groups = $user['groups'];
				
				$curtime = date('Y-m-d H:i:s');
				
				$this->last_login = $user['last_login'];
				
				if (!$this->active)
					$this->error = 'Inactivated User';
			
				else if ($this->banned)
					$this->error = 'Banned User'; 
			
				else
				{
					$this->logged = true;
					$_SESSION[$this->prefix.'id'] = $this->id;
				}
			}
			
			else
				$this->error = 'Invalid User Data';		
		}
		
		else 
			$this->error = 'Incorrect Username or Password'; 
	}
	
	public function login_form()
	{
		return '<form id="webwon-login-form" action="'. $_SERVER['REQUEST_URI'] .'" method="post">' .
				'Username : <input name="'.$this->prefix.'user" type="text" /><br/>' .
				'Password : <input name="'.$this->prefix.'pass" type="password" /><br/>' .
				'<button type="submit">Login</button>'.				
			'</form>' .
		'<script>document.forms[0].'.$this->prefix.'user.focus();</script>';		
	}
	
	public function logout()
	{
		if (isset($_SESSION[$this->prefix.'id']))
			unset($_SESSION[$this->prefix.'id']);
	}
	
	public function member_of($group)
	{
		if (in_array($group, $this->groups))
			return true;
		else
		{
			$this->error = 'You are not a member of ' . $group;
			return false;
		}
	}	
	
	
	public function write_log($action, $value)
	{
		Won::get('DB')->sql->query("
			INSERT INTO {$this->log_table}
			SET user_id = '{$this->id}',
				action = '{$action}',
				value = '{$value}'
		") or die(Won::get('DB')->sql->error);
	}
	


// USER RELATED ///////////////////////////////////////////////////////////////////////////////
	public function get_users()
	{
		$users = Won::get('DB')->sql->query("
			SELECT 	u.id AS id,
					u.username AS username,
					u.password AS password,
					u.name AS name,
					u.email AS email,
					u.active AS active,
					u.banned AS banned,
					u.created_time AS created_time,
					(
						SELECT GROUP_CONCAT(g.name SEPARATOR '-sep-') 
						FROM {$this->group_table} g,
							 {$this->group_membership_table} gm
						WHERE g.id =  gm.group_id AND gm.user_id = u.id				
					) AS groups,
					(
						SELECT l.value FROM {$this->log_table} l
						WHERE l.action = 'login' AND l.user_id = u.id
						ORDER BY l.value DESC
						LIMIT 1						
					) AS last_login				
			FROM 	{$this->user_table} u						
		") or die(Won::get('DB')->sql->error);
		
		$data = array();
		if ($users->num_rows)
		{
			while ($user = $users->fetch_assoc())
			{
				$user['groups'] = $user['groups']? explode('-sep-', $user['groups']) : array();
				$data[] = $user;
			}
		}
		return $data;
	}
	
	
	public function get_user_by_id($userid)
	{
		$user = Won::get('DB')->sql->query("
			SELECT 	u.id AS id,
					u.username AS username,
					u.password AS password,
					u.name AS name,
					u.email AS email,
					u.active AS active,
					u.banned AS banned,
					u.created_time AS created_time,
					(
						SELECT GROUP_CONCAT(g.name SEPARATOR '-sep-') 
						FROM {$this->group_table} g,
							 {$this->group_membership_table} gm
						WHERE g.id =  gm.group_id AND gm.user_id = u.id				
					) AS groups,
					(
						SELECT l.value FROM {$this->log_table} l
						WHERE l.action = 'login' AND l.user_id = u.id
						ORDER BY l.value DESC
						LIMIT 1						
					) AS last_login				
			FROM 	{$this->user_table} u					
			WHERE	u.id='{$userid}'					
		") or die(Won::get('DB')->sql->error);
		
		if ($user->num_rows)
		{
			$user = $user->fetch_assoc();
			$user['groups'] = $user['groups']? explode('-sep-', $user['groups']) : array();
			return $user;	
		}
		else
			return false;
	}
	
	public function get_user_by_username($username)
	{
		$user = Won::get('DB')->sql->query("
			SELECT 	u.id AS id,
					u.username AS username,
					u.password AS password,
					u.name AS name,
					u.email AS email,
					u.active AS active,
					u.banned AS banned,
					u.created_time AS created_time,
					(
						SELECT GROUP_CONCAT(g.name SEPARATOR '-sep-') 
						FROM {$this->group_table} g,
							 {$this->group_membership_table} gm
						WHERE g.id =  gm.group_id AND gm.user_id = u.id				
					) AS groups,
					(
						SELECT l.value FROM {$this->log_table} l
						WHERE l.action = 'login' AND l.user_id = u.id
						ORDER BY l.value DESC
						LIMIT 1	
					) AS last_login				
			FROM 	{$this->user_table} u					
			WHERE	u.username='{$username}'					
		") or die(Won::get('DB')->sql->error);
		
		if ($user->num_rows)
		{
			$user = $user->fetch_assoc();
			$user['groups'] = $user['groups']? explode('-sep-', $user['groups']) : array();
			return $user;			
		}
		else
			return false;
	}
	
		
	
	
	
	
	
	
	public function add_user($username, $password, $name, $email, $active=true, $banned=false)
	{
		Won::set(new Validate());
		
		$username = Won::get('Validate')->username($username);		
		$password = Won::get('Validate')->password($password);
		$name = Won::get('Validate')->name($name);
		$email = Won::get('Validate')->email($email);
		$active = intval($active);
		$banned = intval($banned);	
		$curtime = date('Y-m-d H:i:s');	
					
					
		if ($username && $password && $name && $email && $active <= 1 && $active >=0 && $banned <= 1 && $banned >=0)			 
		{
			$password = md5($password);
						
			if ($this->get_user_by_username($username))
			{
				$this->error = 'Username is already taken';
				return false;
			}		
			
			Won::get('DB')->sql->query("
				INSERT INTO `{$this->user_table}`
				SET `username` = '{$username}',
					`password` = '{$password}',
					`name` = '{$name}',
					`email` = '{$email}',
					`active` = '{$active}',
					`banned` = '{$banned}',
					`created_time` = '{$curtime}'					
			") or die(Won::get('DB')->sql->error);
			return true;
		}
		
		else
		{
			$this->error = implode('<br/>', Won::get('Validate')->error);
			return false;
		}
	}
	
	public function remove_user($id)
	{
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->user_table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_user_name($id, $name)
	{
		Won::set(new Validate());
		$name = Won::get('DB')->sql->real_escape_string(trim($name));		
		$name = Won::get('Validate')->name($name);
		
		if (!$name) 
		{
			$this->error = implode('<br/>', Won::get('Validate')->error);
			return false;
		}
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->user_table}`
			SET `name` = '{$name}'				
			WHERE `id` = '{$id}'							
		") or die(Won::get('DB')->sql->error);		
	}
	
	public function update_user_password($id, $password)
	{
		Won::set(new Validate());
		$password = Won::get('Validate')->password($password);
		
		if (!$password)
		{
			$this->error = Won::get('Validate')->error[0];
			return false;
		}
		
		$password = md5($password);
		Won::get('DB')->sql->query("
			UPDATE `{$this->user_table}`
			SET `password` = '{$password}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
		
		return true;
	}
	
	public function update_user_email($id, $email)
	{
		Won::set(new Validate());
		$email = Won::get('Validate')->email($email);
		
		if (!$email)
		{
			$this->error = Won::get('Validate')->error[0];
			return false;
		}
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->user_table}`
			SET `email` = '{$email}'				
			WHERE `id` = '{$id}'							
		") or die(Won::get('DB')->sql->error);	
	}
	
	public function update_user_active($id, $value)
	{
		Won::get('DB')->sql->query("
			UPDATE`{$this->user_table}`
			SET `active` = '{$value}'				
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_user_banned($id, $value)
	{
		Won::get('DB')->sql->query("
			UPDATE`{$this->user_table}`
			SET `banned` = '{$value}'				
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	


// GROUP RELATED //////////////////////////////////////////////////////////////////////
	
	public function add_group($groupname, $editable=true)
	{
		$groupname = trim(strip_tags($groupname));
		$editable = intval($editable);
		
		if (!$groupname)
		{
			$this->error = 'Cannot be empty groupname.';
			return false;
		}
		
		$exists = Won::get('DB')->sql->query("
			SELECT COUNT(id) FROM {$this->group_table}
			WHERE name = '{$groupname}'
		") or die(Won::get('DB')->sql->error);
		$exists = $exists->fetch_row();
		$exists = $exists[0];
		
		if ($exists > 0)
		{
			$this->error = 'Groupname already exists.';
			return false;
		}
		
		$numrow = Won::get('DB')->sql->query("
			SELECT COUNT(id) FROM {$this->group_table}
		") or die(Won::get('DB')->sql->error);	
		$numrow = $numrow->fetch_row();
		$numrow = $numrow[0];
		
		Won::get('DB')->sql->query("
			INSERT INTO {$this->group_table}
			SET name = '{$groupname}',
				editable = '{$editable}',
				sort_order = '{$numrow}'				 
		") or die(Won::get('DB')->sql->error);
		
		return true;
	}
	
	public function update_group_sort($ids, $start=0)
	{
		$ids = explode(',', $ids);
		$to = count($ids) + $start;
		for ($i=$start; $i<$to; $i++)
		{
			Won::get('DB')->sql->query("
				UPDATE `{$this->group_table}`
				SET `sort_order` = '{$i}'
				WHERE `id` = '{$ids[$i]}'
			") or die(Won::get('DB')->sql->error);
		}
	}
	
	public function remove_group($id)
	{
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->group_table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_group_name($id, $name)
	{
		$name = Won::get('DB')->sql->real_escape_string(trim($name));		
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->group_table}`
			SET `name` = '{$name}'				
			WHERE `id` = '{$id}'							
		") or die(Won::get('DB')->sql->error);		
	}
	
	public function get_group($id)
	{
		$cat = Won::get('DB')->sql->query("
			SELECT * FROM `{$this->group_table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
		
		if ($cat->num_rows)
			return $cat->fetch_assoc();			
	}
	
	public function get_groups()
	{
		$groups = Won::get('DB')->sql->query("
			SELECT * FROM {$this->group_table}
			ORDER BY `sort_order`
		") or die(Won::get('DB')->sql->error);
		
		$list = array();
		if ($groups->num_rows)
		{
			while ($group = $groups->fetch_assoc())
			{
				$list[] = $group;
			}
		}
		return $list;
	}
	
	public function add_user_to_default_group($username)
	{
		$group = Won::get('DB')->sql->query("
			SELECT `name` FROM {$this->group_table}
			ORDER BY sort_order DESC
			LIMIT 1
		") or die(Won::get('DB')->sql->error);
		if (!$group->num_rows)
		{
			return false;
		}
		$group = $group->fetch_assoc();
		
		if ($r = $this->add_user_to_group($username, $group['name']))
			return true;
		else
		{
			$this->error = $r->error;
			return false; 
		}
	}
	
	public function add_user_to_group($username, $groupname)
	{
		Won::set(new Validate());
		$username = Won::get('Validate')->username($username);
		$groupname = trim(strip_tags($groupname));
		if (!$username || !$groupname)
		{
			$this->error = 'Cannot add a user to a group.';
			return false;
		}
		
		$ids = Won::get('DB')->sql->query("
			SELECT u.id AS user_id,
				   g.id AS group_id
			FROM {$this->user_table} u,
			     {$this->group_table} g
			WHERE u.username = '{$username}'
			AND   g.name = '{$groupname}'
		") or die(Won::get('DB')->sql->error);
		
		if (!$ids->num_rows)
		{
			$this->error = 'Invalid Username or Groupname.';
			return false;
		}
		
		$ids = $ids->fetch_assoc();
		
		
		$exists = Won::get('DB')->sql->query("
			SELECT COUNT(id) 
			FROM {$this->group_membership_table}
			WHERE group_id = '{$ids['group_id']}'
			AND   user_id = '{$ids['user_id']}'
		") or die(Won::get('DB')->sql->error);
		$exists = $exists->fetch_row();
		$exists = $exists[0];
		
		if ($exists > 0)
		{
			$this->error = 'The user was already added into the group.';
			return false;
		}
		
		Won::get('DB')->sql->query("
			INSERT INTO {$this->group_membership_table}
			SET group_id = '{$ids['group_id']}',
				user_id = '{$ids['user_id']}' 
		") or die(Won::get('DB')->sql->error);
		
		return true;
	}
	
	public function remove_user_from_group($username, $groupname)
	{
		$userid = Won::get('DB')->sql->query("
			SELECT id FROM {$this->user_table}
			WHERE username = '{$username}'
		") or die(Won::get('DB')->sql->error);
		
		if ($userid->num_rows)
		{		
			$userid = $userid->fetch_assoc();
			$userid = $userid['id'];
		}
		
		$groupid = Won::get('DB')->sql->query("
			SELECT id FROM {$this->group_table}
			WHERE name = '{$groupname}'
		") or die(Won::get('DB')->sql->error);
		
		if ($groupid->num_rows)	
		{	
			$groupid = $groupid->fetch_assoc();
			$groupid = $groupid['id'];
		}
				
		
		Won::get('DB')->sql->query("
			DELETE FROM {$this->group_membership_table}
			WHERE user_id = '{$userid}'
			AND group_id = '{$groupid}'
		") or die(Won::get('DB')->sql->error);
		
		return true;
	}
	
	

}
?>