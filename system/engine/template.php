<?php
final class Template {
	
	private $reg;
	private $htmlHeaders;
	
	public $charset = 'utf-8';
	
	public function __construct($registry) {
		$this->reg = $registry;
		$this->htmlHeaders = array();
		$this->htmlHeaders[] = 'Content-Type: text/html; charset='.$this->charset;
	}
	
	public function __get($class) {		
		return $this->reg->$class;
	}
	
	public function addScriptOnce($scriptId,$src){
		return '<script>(function(d,s,id){var js,fjs=d.body.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement("script");js.id=id;js.type="text/javascript";js.src="'.$src.'";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","'.$scriptId.'"));</script>';
	}
	
	public function addStyleOnce($styleId,$href){
		return '<script>(function(d,s,id){var link,fjs=d.body.getElementsByTagName(s)[0];if(!d.getElementById(id)){link=d.createElement("link");link.id=id;link.type="text/css";link.href="'.$href.'";fjs.parentNode.insertBefore(link,fjs);}}(document,"script","'.$styleId.'"));</script>';
	}
	
	public function output() {
				
		ob_start();
		
		// This is where all the template files are resides on //
		if (file_exists($this->url->template)) {
			require $this->url->template;
		}
		
		$html = ob_get_contents();	
				
		ob_end_clean();
		
		if(isset($bits))
			$html = $this->replace($bits,$html);
			
		$html = $this->compress($html);
		
		if (!headers_sent()) {
			foreach ($this->htmlHeaders as $header) {
				header($header, true);
			}
		}
		
		echo $html;
	}
	
	
	private function replace($bits,$template){
		
		$out=preg_replace('#\t|\n|\r|\s\s+#','',$template);		
		foreach($bits as $bit=>$replace){
			if(gettype($replace)=='array'){
				preg_match_all('#\{\$'.$bit.'\}(.*?)\{\/\$'.$bit.'\}#',$out,$matches);
				for($i=0;$i<count($matches[0]);$i++){
					$str=$matches[0][$i];
					$c='';
					foreach($replace as $loop){
						$new=$matches[1][$i];
						foreach($loop as $b=>$r){
							$new=str_replace('{$'.$b.'}',$r,$new);
						}
						$c.=$new;
					}
					$out=str_replace($str,$c,$out);				
				}
			}
			else
				$out=str_replace('{$'.$bit.'}',$replace,$out);		
		}
		return $out;
	}
	
	
	private function compress ($data, $level=9) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}
		elseif (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';			
		}
		
		if (!isset($encoding) || !extension_loaded('zlib') || ini_get('zlib.output_compression') || headers_sent()) {
			return $data;
		}
		
		$this->htmlHeaders[] = 'Content-Encoding: ' . $encoding;
		return gzencode($data, (int)$level);
	}
	
	// Load the template file from template folder
	public function load($template) {
		
		if (file_exists($specific = $this->config->contentDir . 'templates/' . $template . $this->url->template))
			include $specific;
			
		elseif (file_exists($default = $this->config->contentDir . 'templates/' . $template . '.default.php'))
			include $default;
		
		else
			trigger_error('Cannot load the template ' . $template);		
	}
}
?>