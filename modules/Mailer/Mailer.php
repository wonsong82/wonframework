<?php
class Mailer extends WonClass
{
// PUBLIC PROPERTIES ////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @name $smtp_host
	 * @desc SMTP Host, should be used for getter only
	 * @type string
	 */
	public $smtp_host;
	
	/**
	 * @name $from_name
	 * @desc Name of the sender, should be used for getter only
	 * @type string
	 */
	public $from_name;
	
	/**
	 * @name $from_email
	 * @desc Email address of the sender, should be used for getter only
	 * @type string
	 */
	public $from_email;
	
						
						


// PRIVATE PROPERTIES //////////////////////////////////////////////////////////////////////////////
	/**
	 * @name $mailer
	 * @desc Instance of PHPMailer Class
	 * @type PHPMailer
	 */
	private $mailer;





// PRIVATE FUNCTIONS //////////////////////////////////////////////////////////////////////////////
		/**
	 * @name initialize()
	 * @desc Initialize table structure, add initiative values, and parse args, and set initial values to the PHPMailer object
	 * @param none	 
	 * @return void
	 */
	protected function init()
	{
		$this->table = Won::get('DB')->prefix . 'mailer';		
		
		Won::get('DB')->sql->query("			
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`smtp_host` VARCHAR(255) NOT NULL DEFAULT '',
				`from_name` VARCHAR(255) NOT NULL DEFAULT '',
				`from_email` VARCHAR(255) NOT NULL DEFAULT '',
				PRIMARY KEY(`id`)							
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`		
		") or die(Won::get('DB')->sql->error);		
		
		// initialize the table
		$table = Won::get('DB')->sql->query("SELECT count(`id`) as `length` FROM `{$this->table}` ") or die(Won::get('DB')->sql->error);
		$table = $table->fetch_assoc();
		
		if ($table['length'] == 0)
		{	
			Won::get('DB')->sql->query("			
				INSERT INTO `{$this->table}`
				SET		`smtp_host` = 'localhost',
						`from_name` = 'Webwon',
						`from_email` = 'info@webwon.com'								
			") or die(Won::get('DB')->sql->error);
		}
		
		// refresh parameters
		$args = $this->parse_args();
		
		// initiate phpmailer
		require 'includes/class.phpmailer.php';
		$this->mailer = new PHPMailer();
		$this->mailer->IsSMTP();
		$this->mailer->CharSet = 'UTF-8';
		$this->host($args['smtp_host']);
		$this->from($args['from_email'] , $args['from_name']);
		$this->reply($args['from_email']);
		
		// set properties
		$this->smtp_host = $args['smtp_host'];
		$this->from_name = $args['from_name'];
		$this->from_email = $args['from_email'];
	}
	
	/**
	 * @name parse_args()
	 * @desc Read from database and parse argments
	 * @param none	 
	 * @return array ['smtp_host', 'from_email', 'from_name'] or false
	 */
	private function parse_args() 
	{
		$args = Won::get('DB')->sql->query("
			SELECT	`smtp_host` ,
					`from_name` ,
					`from_email`
			FROM `{$this->table}`
		");
		
		if ($args->num_rows) {
			$arg = $args->fetch_assoc();
			return array(
				'smtp_host'	=>	$arg['smtp_host'],
				'from_name'	=>	$arg['from_name'],
				'from_email'=>	$arg['from_email']
			);						
		}
		
		else
			return false;
	}
	


// PUBLIC FUNCTIONS ///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * @name host($host)
	 * @desc Set the SMTP Host address for the Mailer
	 * @param string $host : SMTP address for the hosting
	 * @return void
	 */
	public function host($host)
	{	
		$this->mailer->Host = $host;
		$this->smtp_host = $host;
	}
	
	/**
	 * @name authenticate($username, $password)
	 * @desc Set SMTP Authenticate Info
	 * @param string $username : SMTP Auth Username
	 * @param string $password : SMTP Auth Password
	 * @return void
	 */
	public function authenticate($username, $password)
	{
		$this->mailer->SMTPAuth = true;
		$this->mailer->Username = $username;
		$this->mailer->Password = $password;
	}
	
	/**
	 * @name subject($subject)
	 * @desc Set subject of the email
	 * @param string $subject : Subject of the email	 
	 * @return void
	 */
	public function subject($subject)
	{
		$this->mailer->Subject = $subject;
	}
	
	
	/**
	 * @name content($html_content)
	 * @desc Set content of the email
	 * @param string $html_content : HTML content 
	 * @return void
	 */
	public function content($html_content)
	{
		$this->mailer->MsgHTML($html_content);
	}
	
	
	/**
	 * @name from($address, $name='')
	 * @desc Set from
	 * @param string $address : Email address of the sender
	 * @param string $name : Full name of the sender
	 * @return void
	 */
	public function from($address, $name='')
	{
		$this->mailer->setFrom($address, $name);
		$this->from_name = $name;
		$this->from_email = $address;
	}
	
	
	/**
	 * @name to($addresses) 
	 * @desc Set to
	 * @param string $addresses : Emails of the receipants, seperated by comma
	 * @return void
	 */
	public function to($addresses) 
	{
		$this->mailer->ClearAddresses(); // remove any previoius addresses
		$adds = explode(',' , trim($addresses)); // comma delimiate addresses			
		foreach ($adds as $add)	 // add addresses for each addresses 
			$this->mailer->AddAddress(trim($add));		
	}
	
	
	/**
	 * @name cc($addresses) 
	 * @desc Set CC
	 * @param string $addresses : Emails of the CC receipants, seperated by comma
	 * @return void
	 */
	public function cc($addresses)
	{
		$this->mailer->ClearCCs(); // remove any previoius addresses
		$adds = explode(',' , trim($addresses)); // comma delimiate addresses
		foreach ($adds as $add)	 // add addresses for each addresses 
			$this->mailer->AddCC(trim($add));			
	}
	
	
	/**
	 * @name bcc($addresses) 
	 * @desc Set BCC
	 * @param string $addresses : Emails of the BCC receipants, seperated by comma
	 * @return void
	 */
	public function bcc($addresses)
	{
		$this->mailer->ClearBCCs(); // remove any previoius addresses
		$adds = explode(',' , trim($addresses)); // comma delimiate addresses
		foreach ($adds as $add)	 // add addresses for each addresses 
			$this->mailer->AddBCC(trim($add));			
	}
	
	
	/**
	 * @name reply($addresses) 
	 * @desc Set Reply to
	 * @param string $addresses : Emails of the reply tos, seperated by comma
	 * @return void
	 */
	public function reply($addresses)
	{
		$this->mailer->ClearReplyTos(); // remove any previoius addresses
		$adds = explode(',' , trim($addresses)); // comma delimiate addresses
		foreach ($adds as $add)	 // add addresses for each addresses 
			$this->mailer->AddReplyTo(trim($add));	
	}
	
	
	/**
	 * @name confirm_to($address)
	 * @desc Set where to send confirmation email to, confirmation checks whether the receipant read the mail or not
	 * @param string $address : Email where confirmation will be sent to
	 * @return void
	 */
	public function confirm_to($address)
	{
		$this->mailer->ConfirmReadingTo = $address;
	}
	
	
	/**
	 * @name add_attachment($path, $name="", $encoding="base64", $type="application/octet-stream")
	 * @desc Add attachment to email, can be used multiple times
	 * @param string $path : File path to the attachment
	 * @param string $name : Name for the attachement
	 * @param string $encoding : Type of the encoding being used
	 * @param string $type : type of the attachment
	 * @return void
	 */
	public function add_attachment($path, $name="", $encoding="base64", $type="application/octet-stream")
	{
		$this->mailer->AddAttachment($path, $name, $encoding, $type);
	}
	
	
	/**
	 * @name clear_attachments()
	 * @desc Clears out all the added attachments
	 * @param none
	 * @return void
	 */
	public function clear_attachments()
	{
		$this->mailer->ClearAttachments();
	}
	
	
	/**
	 * @name send()
	 * @desc Send email, returns true or false whether the mail has beent sent successfully
	 * @param none
	 * @return void
	 */
	public function send()
	{
		return $this->mailer->send();
	}
	
		
	
	/**
	 * @name update($key, $value)
	 * @desc Updates the database entry for $key, $value
	 * @param string $key : Database field name
	 * @param mixed $value : Value for the field
	 * @return void
	 */
	public function update($key, $value)
	{
		$key;
		$value = Won::get('DB')->sql->real_escape_string(trim($value));
		
		Won::get('DB')->sql->query("			
			UPDATE `{$this->table}`
			SET		`{$key}` = '{$value}'			
		");			
	}
}

?>