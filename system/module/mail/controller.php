<?php
namespace app\module;
final class MailController extends \app\engine\Controller{
	
	public $sender; // PhpMailerObj;
		
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		
		// Import Packages
		$this->sender = $this->loader->getClass('mail.Mailer');	
		
		// Set up the sender
		$configs = $this->getConfig();
		$this->sender->charSet = 'UTF-8';
		if((int)$configs['is_smtp']==1){
			$this->sender->isSMTP();
			$this->sender->host = $configs['host'];
			if((int)$configs['is_auth']==1){
				$this->sender->SMTPAuth = true;
				$this->sender->username = $configs['auth_user'];
				$this->sender->password = $configs['auth_pass'];
			}
			$this->sender->port = (int)$configs['port'];
			$this->sender->SMTPSecure = $configs['secure'];
		}
		else{
			$this->sender->isMail();
		}		
	}
	
	
	// @override
	// Add more stuffs when updating DB
	public function updateDB(){
		parent::updateDB();
		$value = $this->model->query("
			SELECT [mail.id] AS [id] FROM [mail]
		");
		if(false===$value){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($value)){
			$result = $this->model->query("
				INSERT INTO [mail] 
				SET		[mail.is_smtp] = 0,
						[mail.host] = '',
						[mail.secure] = '',
						[mail.is_auth] = 0,
						[mail.auth_user] = '',
						[mail.auth_pass] = '',
						[mail.port] = 25
			");
			if(false===$result){
				$this->error = $this->db->lastError();
				return false;
			}
		}	
		return true;	
	}
	
	
	public function getConfig(){
		$configs=array();
		$result = $this->model->query("
			SELECT	[mail.is_smtp] AS [is_smtp],
					[mail.host] AS [host],
					[mail.secure] AS [secure],
					[mail.is_auth] AS [is_auth],
					[mail.auth_user] AS [auth_user],
					[mail.auth_pass] AS [auth_pass],
					[mail.port] AS [port]
			FROM [mail]
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return $result[0];
	}
	
	public function defaultPreset(){
		$result = $this->model->query("
			UPDATE 	[mail]
			SET		[mail.is_smtp] = 0,
					[mail.host] = '',
					[mail.secure] = '',
					[mail.is_auth] = 0,
					[mail.auth_user] = '',
					[mail.auth_pass] = '',
					[mail.port] = 25
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	public function gmailPreset(){
		$result = $this->model->query("
			UPDATE  [mail]
			SET		[mail.is_smtp] = 1,
					[mail.host] = 'smtp.gmail.com',
					[mail.secure] = 'ssl',
					[mail.is_auth] = 1,
					[mail.auth_user] = 'yourgmail@gmail.com',
					[mail.auth_pass] = 'yourgmailpassword',
					[mail.port] = 465
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	// @override
	// Update Individual Fields
	public function update($field, $val){
		
		
		// Validate
		if(!$this->model->field($field)->validate($val)){
			$this->error = 'Field Validation Error';
			return false;
		}
				
		// values		
		if(is_bool($val)) $val = (int)$val;
		$val = $this->db->escape($val);			
		
		// Update
		$table = preg_replace('#\.[a-zA-Z-_]+$#', '', $field);
		$result = $this->model->query("
			UPDATE [{$table}]
			SET [{$field}] = '{$val}'
		");		
		
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		return true;	
	}
	
}
?>