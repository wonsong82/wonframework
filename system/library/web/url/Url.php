<?php
final class Url {
	
	//Parse URL Information from $_SERVER
	public function getUrlFromRequest() {
		$web['protocol'] = $this->getProtocol($_SERVER['SERVER_PROTOCOL']);
		$web['host'] = $_SERVER['HTTP_HOST'];
		$web['uri'] = $_SERVER['REQUEST_URI'];
		$web['url'] = implode('',$web);
		return $web;
	}
	
	//Parse URL Information from given site address
	public function getUrlFromSite($site) {
		$web['protocol'] = $this->getProtocol($site);
		$web['host'] = $this->getHost($site);
		$web['uri'] = $this->getUri();
		$web['url'] = implode('',$web);
		return $web;
	}
	
	//Get the URI from given URL, subtracting the site address from it
	public function getRealUri($site, $url) {
		return trim(str_replace($site, '', $url),'/');
	}
	
	//Get HTTP or HTTPS
	public function getProtocol($var) {		
		preg_match('/https?/i', $var, $protocolMatch);		
		return isset($protocolMatch[0])? 
			strtolower($protocolMatch[0]).'://' : false;
	}
	
	//Get domain name from URL
	public function getHost($url) {
		$bits = explode('/', trim(preg_replace('#^.*?://#i', '', $url)));
		return $bits[0];
	}
	
	//Get requested URI always with / at the end
	public function getUri() {
		return rtrim(trim($_SERVER['REQUEST_URI']), '/').'/';
	}
	
	//Get args from dynamic URI, Dynamic variable is defined by $
	public function getArgs($uriStructure, $uri) {
		if (!strstr($uriStructure, '$')) 
			return array();
		
		$args = array(); //the return array		
		$i=0;			
		$argKeys = array();
		foreach (explode('/', $uriStructure) as $bit) {
			if (preg_match('/^\$/', $bit)) {
				$argKeys[] = array('index'=>$i, 'key'=>str_replace('$','',$bit));
			}
			$i++;
		}		
		$argVals = explode('/', $uri);
		foreach ($argKeys as $argKey) {
			$args[$argKey['key']] = isset($argVals[$argKey['index']])? $argVals[$argKey['index']] : '';
		}
		
		return $args;	
	}
	
	//Read getString and return the associative array
	public function parseGet($getString) {
		$args = array();
		if (preg_match('/=/', $getString)) {
			foreach (explode('&', $getString) as $arg) {
				preg_match('/^(.+)=(.+)$/', $arg, $matches);
				$args[$matches[1]] = $matches[2];
			}
		}
		return $args;
	}
	
	//Return url friendly name from String
	public function urlFriendlyName($name) {
		$name = strtolower(strip_tags(trim($name))); //strip tags and trim it.
		$name = preg_replace('/[^a-zA-Z0-9-_\s]/s', '', $name); //Remove Special char
		$name = preg_replace('/\s{2,}/', ' ', $name); //Make space only once
		$name = str_replace(' ', '-', $name); //change space to dash
		return $name;
	}

}
?>