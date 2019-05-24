<?php
class Googlemap{
	
	public function getMaplink($address,$lang='en',$z=15){
		
		$address=preg_replace('#\s\s?#','+',$address);
		$map='http://maps.google.com';
		if($lang=='ko')
			$map='http://maps.google.co.kr';
		return $map.'/maps?f=q&source=s_q&hl='.$lang.$address.'&z='.$z;
	}
	
	
	public function getEmbeddedMap($address,$width,$height,$lang='en',$z=15){
		
		$width=(int)$width;
		$height=(int)$height;
		$address=preg_replace('#\s\s?#','+',$address);
		$map='http://maps.google.com';
		if($lang=='ko')
			$map='http://maps.google.co.kr';
		return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$map.'/maps?f=q&source=s_q&hl='.$lang.'&q='.$address.'&z='.$z.'&output=embed"></iframe>';
	}
}
?>