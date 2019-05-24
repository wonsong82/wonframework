<?php
class Permalink
{
	/**
	 * @name $uri
	 * @desc URI of the current requested page.
	 * @type string
	 */
	public $uri;
	
	
	/**
	 * @name $title
	 * @desc Title of the current requested page.
	 * @type string
	 */
	public $title;
	
	
	/**
	 * @name $template
	 * @desc Template file name of the current requested page.
	 * @type string
	 */
	public $template;
	
	
	/**
	 * @name $url
	 * @desc Full URL of the current requested page.
	 * @type string
	 */
	public $url;
	
	
	/**
	 * @name $params
	 * @desc Array of parameters if the current requested page is a dynamic page with variables.
	 * @type array
	 */
	public $params = array();
	
	
	/**
	 * @name $table
	 * @desc Database table name.
	 * @type string
	 */
	private $table;
	
	
	
		
	/**
	 * @name Permalink()
	 * @desc Create dynamic/static URL friendly link structure to the site.
	 * @param none	 
	 * @return void
	 */
	public function __construct()
	{
		// load config
		if (!defined('CONFIG_LOADED'))
			require_once '../../config.php';
		
		// initialize table
		$this->table = DB_PREFIX . 'permalink';
		$this->initialize_table();		
		
		// parse url information from the current request	
		$this->parse_url();			
		
		// parse template information from the current url information
		$this->parse_template();			
	}
	
	
	
		
	/**
	 * @name initialize_table()
	 * @desc Initialize the table and add the error page if not exists
	 * @param none
	 * @return void
	 */
	private function initialize_table() 
	{	
		global $sql;
		
		//if table not exist, create the table
		$sql->query("		
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`uri` VARCHAR(255) NOT NULL,
				`title` VARCHAR(255) NOT NULL,
				`template_path` VARCHAR(255) NOT NULL,				
				PRIMARY KEY (`id`),
				UNIQUE (`uri`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`			
		") or die($sql->error);		
		
		// add error page if it does not exist 
		$errorpage = $sql->query("		
			SELECT * FROM `{$this->table}`
			WHERE `uri` = '404error'		
		") or die($sql->error);
					
		if (!$errorpage->num_rows)
			$this->add('/404error', 'Page Cannot Be Found', '404error.php');
	}
	
	
	
		
	/**
	 * @name parse_url()
	 * @desc Parse URI and URL. Redirect if the requested url is differ from host information from config.php.
	 * @param none
	 * @return void
	 */	
	private function parse_url()
	{
		// first, parse url information from request
		// this contains real uri ('src/install') as opposed to site uri ('/install')
		$req = $this->parse_url_from_request();
						
		// then, parse url information from config
		// this is how it supposed to be
		$con = $this->parse_url_from_config();
		
		// then redirect if the req url doesnt match with protocol (https) or host (www)
		// or the uri case sensitivity is different
		if ($req->url != $con->url)
		{			
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: ' . $con->url); 	
		}
		
		// get real uri for parsing template
		$this->url = trim($con->url, '/');
		$this->uri = trim(str_replace(SITE_URL, '', $con->url) , '/');		
		
	}
	
	
	
	/**
	 * @name parse_url_from_request()
	 * @desc Get Protocol, Host, URI, URL from the server request.
	 * @param none
	 * @return void
	 */		
	private function parse_url_from_request()
	{	
		// get protocol http or https
		preg_match('#https?#i', $_SERVER['SERVER_PROTOCOL'] , $protocol);		
		$req->protocol = strtolower($protocol[0]).'://';
		
		$req->host = $_SERVER['HTTP_HOST'];
		$req->uri = $_SERVER['REQUEST_URI'];		
		$req->url = $req->protocol . $req->host . $req->uri;
		
		return $req;	
	}
	
	
	
	/**
	 * @name parse_url_from_config()
	 * @desc Get Protocol, Host, URI, URL from the config.php.
	 * @param none
	 * @return void
	 */	
	private function parse_url_from_config()
	{
		// get protocol http or https
		preg_match('#https?#i', SITE_URL, $protocol);		
		$config_url->protocol = strtolower($protocol[0]). '://';	
		
		$host = explode('/', trim(str_replace($config_url->protocol, '', SITE_URL)));	
			
		$config_url->host = $host[0];
		$config_url->uri = $_SERVER['REQUEST_URI'];
		$config_url->url = $config_url->protocol . $config_url->host . $config_url->uri;		
		
		return $config_url;		
	}
	
	
	
	/**
	 * @name parse_template()
	 * @desc Parse template details from the requested URI, either static, dynamic or 404error template. Sets Permalink->params if there are any parameters passed. 
	 * @param none
	 * @return void
	 */			
	private function parse_template()
	{
		global $sql;
		
		$uri = $this->uri;		
		
		// first find static pages			
		$templates = $sql->query("		
			SELECT * FROM `{$this->table}`
			WHERE `uri` = '$this->uri'		
		") or die($sql->error);
		
		// if static page is found
		if ($templates->num_rows)
		{
			$template = $templates->fetch_assoc();
			$this->title = $template['title'];			
			$this->template = $template['template_path'];
			return true;
		}
		
		// if static page isn't found
									
		// dynamic uris
		$dynamic_uris = $sql->query("			
			SELECT * FROM `{$this->table}`
			WHERE `uri` LIKE '%\$%'			
		") or die ($sql->error);
		
		if ($dynamic_uris->num_rows)
		{
			while ($dynamic_uri = $dynamic_uris->fetch_assoc())
			{			
				if (preg_match('#$#', $dynamic_uri['uri']))
				{
					$d_uri = explode('$', $dynamic_uri['uri']);
					$d_uri = trim($d_uri[0], '/');
					
					if (preg_match('#^'.$d_uri.'#i' , $uri))
					{
						$this->template = $dynamic_uri['template_path'];
						$this->title = $dynamic_uri['title'];
						
						$keys = explode('/', $dynamic_uri['uri']);
						$values = explode('/', trim(str_replace($d_uri, '', $uri), '/'));					
						
						$c = 0;												
						foreach ($keys as $key)
						{
							if (substr($key, 0, 1) == '$')
							{
								$key = str_replace('$','',$key);
								$this->params[$key] = isset($values[$c])? $values[$c] : '';
								$c++;									
							}						
						}
						
						return true;
					}
				}							
			}			
		}	
		
		
		// if cannot be found, display 404 error template			
		$templates = $sql->query("
		
		SELECT * FROM `{$this->table}`
		WHERE `uri` = '404error'
		
		") or die($sql->error);
		
		if ($templates->num_rows)
		{
			$template = $templates->fetch_assoc();
			$this->title = $template['title'];			
			$this->template = $template['template_path'];
		}
		
		return false;		
	}
	
	
	
	/**
	 * @name get_list()
	 * @desc Returns array of all link list.
	 * @param none
	 * @return array
	 */	
	public function get_list()
	{
		global $sql;
		
		$links = $sql->query("
			SELECT * FROM `{$this->table}`
			ORDER BY `id`
		") or die($sql->error);
		
		$list = array();
		
		if ($links->num_rows)		
			while ($link = $links->fetch_assoc()) 			
				$list[] = $link;	
		
		return $list;
	}
	
	
	/**
	 * @name add(string $uri, string $title, string $template_file)	 
	 * @desc Add the link to the database and create the template file in content dir if not exist.	 
	 * @param string $uri : URI of the page.
	 * @param string $title : Title of the page.
	 * @param string $template_file : Template file name.	 
	 * @return void
	 */
	public function add($uri, $title, $template_path)
	{
		global $sql;
				
		// uri should not contain '/' at the beginning or at the end
		$uri = $sql->real_escape_string(trim(trim($uri),'/'));		
		
		// title must be escaped
		$title = $sql->real_escape_string(trim($title));
			
		$sql->query("			
			INSERT INTO `{$this->table}` 
			SET `uri` = '$uri',
				`title` = '$title',
				`template_path` = '$template_path'			
		") or die($sql->error);		
		
		$this->create_template($template_path);
	}

	
	
	
	/**
	 * @name remove(int $id)
	 * @desc Remove a permalink from database.
	 * @param int $id : id of the row to be removed.
	 * @return void
	 */	
	public function remove($id)
	{
		global $sql;
		
		$sql->query("			
			DELETE FROM `{$this->table}`
			WHERE `id` = '{$id}'			
		") or die($sql->error);
	}
	
	
	
	/**
	 * @name update(int $id, string $key, string $value)
	 * @desc Update a permalink from database
	 * @param int $id : id of the row to be modified.
	 * @param string $key : key of the item to be modified.
	 * @param string $value : value of the key of the item to be modified. 
	 * @return void
	 */		
	public function update($id, $key, $value)
	{
		global $sql;
		
		$sql->query("
		
			UPDATE `{$this->table}`
			SET		`{$key}` = '{$value}'
			WHERE `id` = '{$id}'
		
		") or die($sql->error);
	
	}
		
	
	
	/**
	 * @name create_template(string $template_file)
	 * @desc Creates the template file if the file not exist
	 * @param string $template_file : File name to be created
	 * @return void
	 */		
	private function create_template($template_path)
	{	
		$template_path = CONTENT_DIR . '/' . $template_path;

		if (!file_exists($template_path))
		{
			$fh = fopen($template_path, 'w') or die("can't create file");
			fwrite($fh, $template_path);
			fclose($fh);			
		}
	}	
	
	
	
}





?>