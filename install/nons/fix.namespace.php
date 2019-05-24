<?php

// This script is to fix the namespaces for this app if the hosting server php version is less than 5.3

$ver = phpversion();

$ver = explode('.',$ver);

$nsAvailable = $ver[0]>5 || $ver[0]==5&&$ver[1]>=3 ? true : false;


if(!$nsAvailable){

	$root = dirname(dirname(dirname(__FILE__)));

	

	$files = rdir($root,true);

	foreach($files as $file){

		if(preg_match('#.php$#',$file)){

			$found=false;

			$nc = file_get_contents($file);

			$c=preg_replace('#\n|\r|\t#','',file_get_contents($file));

						

			$initClass = '#new [a-zA-Z\-\_\\\\]+\(.*?\)\;#';						

			preg_match_all($initClass, $c, $matches);

			$initClassMatches=array();

			foreach($matches[0] as $match){

				if(strstr($match,"\\")){

					$initClassMatches[]=$match;

				}

			}

			if(count($initClassMatches)>0) $found=true;

			

			$namespace = '#namespace [a-zA-Z\\\\]+;#';

			preg_match_all($namespace, $c, $matches);

			$namespaceMatches=array();

			foreach($matches[0] as $match){

				$namespaceMatches[] = $match;

			}

			if(count($namespaceMatches)>0) $found=true;

			

			

			if($found){

				copy($file, $file.'.bak');

				foreach($initClassMatches as $initClassMatch){

					$newInitClass = str_replace("\\",'_',$initClassMatch);

					$newInitClass = preg_replace("#(new )_([a-zA-Z])#", '$1$2', $newInitClass);

					$nc = str_replace($initClassMatch ,$newInitClass, $nc);										

				}

				

				foreach($namespaceMatches as $nsMatch){

					$newClassPrefix = str_replace("\\",'_',str_replace(';','_',str_replace('namespace ','',$nsMatch)));

					$nc = str_replace($nsMatch,'',$nc);

					$nc = preg_replace("#class ([a-zA-Z])#", 'class '.$newClassPrefix."$1", $nc);

					preg_match_all("# extends .+?\{#",$c,$matches);

					if(count($matches[0]>0)){

						foreach($matches[0] as $match){

							if(strstr($match,"\\")){

								$newExtend = str_replace("\\",'_',$match);

								$newExtend = preg_replace("#(extends )_([a-zA-Z])#", '$1$2',$newExtend);

								$nc = str_replace($match, $newExtend, $nc);

							}

						}

					}				

				}

				

				

				file_put_contents($file, $nc);

			}					

		}	

	}

	

	$nonsFiles = array(

		'/system/engine/Admin.php', 

		'/system/engine/AdminView.php',

		'/system/engine/Controller.php',

		'/system/engine/Loader.php',

		'/system/engine/Registry.php',

		'/system/engine/datatype/Table.php',

		'/system/engine/displayobj/DisplayObject.php',
		
		'/system/engine/datatype/DataType.php',
		
		'/system/com.won/graphic/Image.php'

	);

	foreach($nonsFiles as $file){

		$original = $root . $file;

		$copy = $root . $file . '.bak';

		if(!file_exists($copy)) copy($original, $copy);

		

		$replace = dirname(__FILE__) . DIRECTORY_SEPARATOR .basename($file) . '.nons';

		copy($replace, $original);

		

		

	}

	

	

}







function rdir($dir, $recursive=false){

	if(!is_dir($dir)) return array();		

	if(!$recursive){

		$files=array();

		$dh=opendir($dir);

		while(false!==($f=readdir($dh))){ 

			if($f!='.'&&$f!='..')

				$files[]=$dir.DIRECTORY_SEPARATOR.$f;

		}

		closedir($dh);

		return $files;	

	}

	else{		

		$dirs=array($dir);

		$files=array();

		while(count($dirs)>0){ //Keep read dirs until $dirs is empty

			$ndir=array(); //New Dir

			foreach($dirs as $dir){

				$dh=opendir($dir);

				while(false!==($f=readdir($dh))){

					if($f!='.'&&$f!='..'){

						$f=$dir.DIRECTORY_SEPARATOR.$f;						

						if(is_dir($f))

							$ndir[]=$f;						

						else

							$files[]=$f;

					}

				}				

				closedir($dh);				

			}

			$dirs=$ndir;

		}

		return $files;

	}

}

?>