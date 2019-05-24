<?php
// namespace app\module;
final class app_module_UrlController extends app_engine_Controller{
	
	public $url;
	public $uri;
	public $structure;
	public $args;
	public $template;
	
	private $urlObj; // Url Handling Class
	private $fileObj;
	
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		
		// Import Packages
		$this->urlObj = $this->loader->getClass('web.Url');
		$this->fileObj = $this->loader->getClass('file.File');
		
		// Parse URL Info
		$url = $this->urlObj->getUrlFromSite($this->config->site);
		$this->url = $url['url'];
		$this->uri = $this->urlObj->getRealUri($this->config->site, $this->url);
				
		// Get Template
		if($this->config->isAjax){ // If Ajax Call
			// ajax/class/method/
			$this->structure = 'ajax/$c/$m';
			$this->args = $this->urlObj->getArgs($this->structure, $this->uri);
			$this->template = $this->config->moduleDir.'url/ajax.php';			
		}
		
		elseif(!$this->config->isAdmin){ // If website
			$template = $this->parseTemplate();
			$this->template = $template['template']? $this->config->contentDir. $template['template'] : false;
			$this->structure = $template['uri'];
			$this->args = $this->urlObj->getArgs($this->structure, $this->uri);
		}
		
		else{ // If Admin
			// admin/page(template)/params(getStirng without ?)
			$this->structure = $this->config->adminUri .'$menu/$args';
			$args = $this->urlObj->getArgs($this->structure, $this->uri);
			$template = 'index.php';
			$this->template = $this->config->adminDir. $template;
			$this->args = $this->urlObj->parseGet($args['args']);
			$this->args['menu'] = $args['menu'];
		}
		
	}
	
	// @override
	// Add more Stuffs when updating DB
	public function updateDB(){
		parent::updateDB();
		
		//Add 404
		$invalid = $this->model->query("
			SELECT [url.id] AS [id] FROM [url]
			WHERE [url.uri] = '404error'
		");
		if(!count($invalid)){
			$this->add('', 'home.php');
			$this->add('404error', '404error.php');
			$this->add('m/fullmode', 'home.php');
			$this->add('m/$page', 'mobile.php');
			
		}
		
		return true;		
	}
	
	public function getLangUrl($langCode){
		$base = $this->lang->isDefault?
			$this->config->site :
			str_replace($this->lang->lang.'/', '', $this->config->site);
		$langCode = $this->lang->defaultLang==$langCode? '' : $langCode.'/';
		return $base . $langCode . $this->uri;		
	}
	
	//
	// Get Template
	protected function parseTemplate(){
		$uri = $this->db->escape($this->uri);
		$result = $this->model->query("
			SELECT [url.uri] AS [uri], [url.template] AS [template] FROM [url]
			WHERE [url.uri] = '{$uri}'
		");
		
		// if Found : Exact Match
		if($result&&count($result))
			return $result[0];
		
		// Next Dynamic Pages
		$result=$this->model->query("
			SELECT [url.uri] AS [uri], [url.template] AS [template] FROM [url]
			WHERE [url.uri] LIKE '%\$%'
		");
		if($result&&count($result)){
			foreach($result as $template){
				$bits=explode('$', $template['uri']);
				$staticUri = trim($bits[0], '/');
				if(preg_match('#^'.$staticUri.'#i', $uri)){
					return $template;
				}
			}
		}
		
		// Not Found
		$result = $this->model->query("
			SELECT [url.uri] AS [uri], [url.template] AS [template] FROM [url]
			WHERE [url.uri] = '404error'
		");
		if($result&&count($result))
			return $result[0];
		else // If Database is missing
			return array('uri'=>'', 'template'=>'');		
	}
	
	
	//
	// Add the row to DB and make a template File
	public function add($uri, $template){
		// Validate
		if(!$this->model->field('url.uri')->validate($uri)){
			$this->error = 'Invalid URI Format';
			return false;
		}
		if(!$this->model->field('url.template')->validate($template)){
			$this->error = 'Invalid Template Format';
			return false; // Validation Error
		}
		// Clean
		$uri = strtolower(trim($this->db->escape($uri),'/'));
		$template = $this->db->escape($template);		
		// Add to DB
		$result = $this->model->query("
			INSERT INTO [url]
			SET [url.uri]='{$uri}', [url.template]='{$template}'
		");
		if(!$result){
			$this->error = 'ADD URL Error';
			return false;
		}
				
		// Make a File if not exists
		if(!file_exists($this->config->contentDir .$template))
			$this->loader->getClass('file.File')->write($this->config->contentDir. $template, $template);
		
		return true;
	}	
	
	//
	// Remove a uri
	public function remove($id){
		$id = (int)$this->db->escape($id);
		$result = $this->model->query("
			DELETE FROM [url]
			WHERE [url.id] = {$id}
		");
		if(!$result){
			$this->error = 'Remove URL Error';
			return false;
		}
		return true;
	}
	
	//
	// Get URIs
	public function getURIs(){
		return $this->model->query("
			SELECT 	[url.id] AS [id],
					[url.uri] AS [uri],
					[url.template] AS [template]
			FROM [url]
			ORDER BY [order]
		");			
	}
	
	public function getUniqueName($name){
		$name = strtolower($this->db->escape($name)); 
		$name = preg_replace('#[^a-z0-9-_\s\/]#s','',$name); // remove special chars
		$name = preg_replace('#\s{2,}#',' ', $name); // one space only
		$name = str_replace(' ','-', $name);
		$name = rtrim($name, '/');		
		
		// Find Duplicates	
		$nextName = false;	
		$result = $this->model->query("
			SELECT [unique_name.id] AS [id]
			FROM [unique_name]
			WHERE [unique_name.name] = '{$name}'
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}		
		
		$i = 2;
		while(count($result) > 0){
			$nextName = $name . '-' . $i;
			$result = $this->model->query("
				SELECT [unique_name.id] AS [id]
				FROM [unique_name]
				WHERE [unique_name.name] = '{$nextUri}'
			");
			if(false===$result){
				$this->error =$this->db->lastError();
				return false;
			}
			$i++;
		}
		
		return $nextName? $nextName : $name;
	}
	
	public function addUniqueName($name){
		$name = $this->getUniqueName($name);
		$result = $this->model->query("
			INSERT INTO [unique_name]
			SET [unique_name.name] = '{$name}'
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return $name;
	}
	
	public function getUniqueNameId($name){
		$name = $this->db->escape($name);
		$result = $this->model->query("
			SELECT [unique_name.id] AS [id]
			FROM [unique_name]
			WHERE [unique_name.name] = '{$name}'
		");
		if(false===$result){
			$this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Unexisting Unique Name';
			return false;
		}
		return (int)$result[0]['id'];
	}
	
	public function removeUniqueName($param){
		if((int)$param > 0){
			$id = $this->db->escape((int)$param);
			$where = "[unique_name.id] = {$id}";
		} else {
			$name = $this->db->escape($param);
			$where = "[unique_name.name] = '{$name}'";
		}
		$result = $this->model->query("
			DELETE FROM [unique_name]
			{$where}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;		
	}
}
?>