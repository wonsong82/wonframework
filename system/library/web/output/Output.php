<?php
class Output{
		
	
	public function readFile($file){
		
		if(!file_exists($file))
			return false;
		ob_start();
		include $file;
		$out=ob_get_contents();
		ob_end_clean();		
		return $out;
	}
	
	
	public function replace($bits,$template){
		
		$out=preg_replace('#\t|\n|\r|\s\s+#','',$out);		
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
	
	
	public function compress($data,$level=9){
		
		if(isset($_SERVER['HTTP_ACCEPT_ENCODING'])&&(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'x-gzip')!==false))
			$encoding='x-gzip';
		elseif(isset($_SERVER['HTTP_ACCEPT_ENCODING'])&&(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')!==false))
			$encoding='gzip';
		if(!isset($encoding)||!extension_loaded('zlib')||ini_get('zlip.output_compression')||headers_sent())
			return $data;
		return array(
			'encoding'=>$encoding,
			'data'=>gzencode($data,(int)$level)
		);	
	}
	
}
?>