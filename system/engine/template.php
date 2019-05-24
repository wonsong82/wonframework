<?php
// Name : Template
// Desc : All the view files will resides with in this Template Class
namespace app\engine;
final class Template{
	
	private $registry;
	private $headers = array();
	private $css = array();
	private $js = array();
	
	// Charset of the html file
	public $charset = 'utf-8';
	
	
	//
	// Constructor
	public function __construct($registry){
		$this->registry = $registry;
		$this->headers[] = 'Content-Type: text/html; charset=' . $this->charset;
	}
	
	
	//u
	// For the access of Engines & Modules from Views
	public function __get($name){
		return $this->registry->$name;
	}
	
	
	//
	// Render out the view file according to the URI
	public function render(){
		// Start the OB to grab the contents into string
		ob_start(); 
		if($this->url->template)
			require_once $this->url->template;
		$contents = ob_get_contents();
		ob_end_clean();
		
		//Compress it
		$out = $this->compress($contents);
		
		//Renders to the browser
		if(!headers_sent()){
			foreach($this->headers as $header)
				header($header, true);
		}
		echo $out;
	}
	
	//
	// Load template part for the current template,
	// Load the default template part (ex: header.default.php) if no specific template defined.
	public function load($part){
		$specificPart = $this->config->contentDir. 'parts/'.$part.'.'.basename($this->url->template);
		$defaultPart = $this->config->contentDir. 'parts/'.$part.'.default.php';
		if(file_exists($specificPart))
			include $specificPart;
		elseif(file_exists($defaultPart))
			include $defaultPart;
		else
			return false;
	}
	
	//
	// Add the Library 
	// Add the Library composed with JS, CSS, and its Requirements
	// Load the Library If its not loaded yet.
	public function addLib($path){
		$req=array();
		$css=array();
		$js=array();
		$data='';
		$info=$this->config->libraryDir . str_replace('.','/',$path).'/info.php';
		$path=$this->config->library . str_replace('.','/',$path);
		
		if(!file_exists($info)){
			trigger_error("cannot locate info file from ".$path);
			return false;
		}
		require $info;
		foreach($req as $r)
			$data.=$this->addLib($r);
		
		foreach($css as $id=>$href){
			if(!in_array($id,$this->css)){
				$this->css[]=$id;
				$data.='<link rel="stylesheet" type="text/css" href="'.$path.'/'.$href.'"/>'."\n";
			}
		}
				
		foreach($js as $id=>$src){
			if(!in_array($id,$this->js)){
				$this->js[]=$id;
				$data.='<script type="text/javascript" src="'.$path.'/'.$src.'"></script>'."\n";		
			}
		}
		
		return $data;
	}
	
	
	//
	// Compress the contents
	private function compress($data, $level=9){
		if (isset($this->req->server['HTTP_ACCEPT_ENCODING']) && (strpos($this->req->server['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}
		elseif (isset($this->req->server['HTTP_ACCEPT_ENCODING']) && (strpos($this->req->server['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}		
		if (!isset($encoding) || !extension_loaded('zlib') || ini_get('zlib.output_compression') || headers_sent()) {
			return $data;
		}		
		$this->headers[] = 'Content-Encoding: ' . $encoding;
		return gzencode($data, (int)$level);
	}
}
?>