<?php
//Format and convert texts, number, etc
class Format{
	
	//$bits:associative array that the {$KEY} will be used to replace the format
	public function text($bits,$format){
		
		foreach($bits as $key=>$val)
			$format=str_replace('{$'.$key.'}',$val,$format);
			
		return $format;
	}
}
?>