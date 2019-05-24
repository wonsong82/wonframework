<?php
class UrlController extends Controller {
	
	public $url;
	public $uri;
	public $uriStructure;
	public $args;
	public $template;
		
	protected function init() {
		
		//Import libraries
		$this->lib->import('io.file');
		$this->lib->import('web.url');		
			
		// 404Error page if firsttime
		if (!$this->model->exist('404error')) {
			$this->add('404error', '404error.php');		
		}
		
		// Parse url information
		$requestUrl = $this->lib->url->getUrlFromRequest();
		$configUrl = $this->lib->url->getUrlFromSite($this->config->site);
		
		//Redirect for following reasons : 1. www or non-www, https?, right/
		if ($requestUrl['url'] != $configUrl['url']) {
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: ' . $configUrl['url']);
			exit();
		}	
		
		//Parse real uri
		$this->url = $configUrl['url'];
		$this->uri = $this->lib->url->getRealUri($this->config->site, $this->url);
		
		//Check if Admin or not
		$isAdmin = preg_match('#^'.$this->config->admin.'#i', $this->url);
		
		if (!$isAdmin) {					
			//Parse Template
			$template = $this->model->getTemplate($this->uri);
			$this->template = $this->config->contentDir.$template['template'];
					
			//Parse Args for dynamic URL
			$this->uriStructure = $template['uri'];			
			$this->args = $this->lib->url->getArgs($this->uriStructure, $this->uri);
		}
		
		else {
			// admin/page(template)/params(getString without ?)
			$this->uriStructure = $this->config->adminPage.'$template/$args';
			$args = $this->lib->url->getArgs($this->uriStructure, $this->uri);
			$template = $args['template']? $args['template'].'.php' : 'main.php';
			$this->template = $this->config->adminContentDir.$template;
			$this->args = $this->lib->url->parseGet($args['args']);			
		}
		
	}
	
	
	public function getUrls() {
		
	}
	
	
	//Add the link to the db & make a template file
	public function add($uri, $template) {
		
		$uri = trim(trim($uri),'/');
		$template = trim($template);
		
		if (!$this->model->validate('url.uri', $uri)) {
			$this->error = $this->model->getError('INVALID_URI');
			return false;			
		}
		
		if (!$this->model->validate('url.template', $template)) {
			$this->error = $this->model->getError('INVALID_TEMPLATE');
			return false;
		}		
		
		if ($this->model->exist($uri)) {
			$this->error = $this->model->getError('EXISTING_URI', $uri);
			return false;
		}
		
		$this->model->add($uri, $template);			
		if (!file_exists($this->config->contentDir.$template)) {			
			$this->lib->file->write($this->config->contentDir.$template, $template);
		}		
	}
		
	
	//Remove the link
	public function remove($id) {
		$id = trim($id);
		$this->model->remove($id);		
	}
	
	
}
?>