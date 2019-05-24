<?php
$NS = array(
	array('app\\engine\\', 'app_engine_'),
	array('app\\module\\', 'app_module_'),
	array('com\\won\\', 'com_won_')
);


$ver = phpversion();
$ver = explode('.',$ver);
$nsAvailable = $ver[0]>5 || $ver[0]==5&&$ver[1]>=3 ? true : false;


$rootDir = dirname(dirname(dirname(__FILE__)));
$rootDir = dirname(__FILE__);


$files = rdir($rootDir, true);
foreach($files as $file){
	if(preg_match('#.php$#',$file) && basename($file)!='namespace.php'){
		
		$found=false;
		$nc=file_get_contents($file); // Replacement Content
		$c=preg_replace('#\n|\r|\t#','',file_get_contents($file)); // Content to Search
		highlight_string($nc);
		
		// Define Namespace and Class Name
		preg_match('#namespace (.+?);#',$nc,$namespace);
		$namespace=$namespace[1];
		
		if($nsAvailable){
			
			// namespace for this
			$thisns = $namespace;
			
			//
			// Namespace Declaration
			$namespaceDefined=false;
			$ms = match('#(\/?\/? ?)(namespace )([a-zA-Z0-9\\\\]+)(\;)#', $nc);
			foreach($ms as $m){
				$match = $m[0];
				$comment = $m[1];
				$key = $m[2];
				$val = $m[3];
				$after = $m[4];
				if(trim($comment)=='//'){
					$val = $val[0]. '-BEINGCHANGED-' . substr($val,1);
					$nc = str_replace($match, $key. $val. $after, $nc);
					$namespaceDefined = true;
				}
			}
			
			if($namespaceDefined){
						
				//
				// Class Declaration
				$ms = match('#class ([a-zA-Z0-9-_]+) #', $nc);
				// For all of class declarations,
				foreach($ms as $m){
					$match=$m[0];
					$class=$m[1];
					$nsStr = str_replace('\\','_',$thisns).'_';
					$class=str_replace($nsStr, '', $class);
					$nc = str_replace($match, 'class '.'-BEINGCHANGED-'. $class.' ',$nc);
				}
				
				//
				// For each namespaces
				foreach($NS as $nss){
					// class with name space must have the name space and end point
					$ms = match('#(.?)('.$nss[1].'[a-zA-Z0-9-_]+)(.?)#', $nc);
					foreach($ms as $m){
						$match = $m[0];
						$before = $m[1];
						$class = $m[2];
						$after = $m[3];
						$classTo = str_replace('_','\\',$class);
						$nc = str_replace($match, $before. '\\'. '-BEINGCHANGED-'. $classTo. $after, $nc);						
					}
					
					// String Literals
					$m2s = match('#(\= ?)(\'|\")(.*?'.str_replace('\\','\\\\',$nss[0]).'.*?)(\'|\")#', $nc);
					foreach($m2s as $m){
						$match = $m[0];
						$equal = $m[1];
						$q1 = $m[2];
						$val = $m[3];
						$q2 = $m[4];
						$valTo = str_replace('\\','\\\\',$val);
						$nc = str_replace($match, $equal.$q1.$valTo.$q2, $nc);
					}				
				}
				
				//
				// Bases
				$ms = match('#(new )(\\\\?)([a-zA-Z0-9-_]+)(.?)#', $nc);
				foreach($ms as $m){
					$match = $m[0];
					$new = $m[1];
					$root = $m[2];
					$class = $m[3];
					$after = $m[4];
					if(trim($root)==''){						
						$nc = str_replace($match, $new. '\\'.'-BEINGCHANGED-'.$class.$after, $nc); 
					}				
				}					
			}		
		} 
		
		
		else {
			
			// namespace for this
			$thisns = str_replace('\\','_',$namespace);
			
			//
			// Namespace Declaration
			$namespaceDefined=false;
			$ms = match('#(\/?\/? ?)(namespace )([a-zA-Z0-9\\\\]+)(\;)#', $nc);
			foreach($ms as $m){
				$match = $m[0];
				$comment = $m[1];
				$key = $m[2];
				$val = $m[3];
				$after = $m[4];
				if(trim($comment)==''){
					$val = $val[0]. '-BEINGCHANGED-' . substr($val,1);
					$nc = str_replace($match, '// '. $key. $val. $after, $nc);
					$namespaceDefined = true;
				}
			}
			
			if($namespaceDefined){
				
			//
			// Class Declaration
			$m = match('#class ([a-zA-Z0-9-_]+) #', $nc);
			// For all of class declarations,
			foreach($m as $class){
				// if the class declaration already contains this namespace, skip it
				if(false===strpos($class[1], $thisns)){
					$nc = str_replace($class[0], 'class '.'-BEINGCHANGED-' . $thisns.'_'.$class[1].' ', $nc);
				}			
			}
			
			//
			// For each namespaces (extends and others)
			foreach($NS as $nss){
				// class with name space must have the name space end end point
				$ms = match('#(.?)(\\\\?)('.str_replace('\\','\\\\',$nss[0]).'[a-zA-Z0-9_\\\\]+)(.?)#', $nc);
				foreach($ms as $m){
					$match = $m[0];
					$before = $m[1];
					$root = $m[2];
					$class = $m[3];
					$after = $m[4];
					
					$rootTo = str_replace('\\', '', $root);
					$classTo = str_replace('\\', '_', $class);
					$nc = str_replace($match, $before. $rootTo. '-BEINGCHANGED-' . $classTo. $after, $nc);				
				}
				// Find String Literals
				$ms = match('#(.?)(\\\\?\\\\?)('.str_replace('\\','\\\\\\\\',$nss[0]).'[a-zA-Z0-9_\\\\]+)(.?)#', $nc);
				foreach($ms as $m){
					$match = $m[0];
					$before = $m[1];
					$root = $m[2];
					$class = $m[3];
					$after = $m[4];
					
					$rootTo = str_replace('\\\\', '', $root);
					$classTo = str_replace('\\\\', '_', $class);
					$nc = str_replace($match, $before. $rootTo. '-BEINGCHANGED-' .$classTo. $after, $nc);
				}				
			}
			
			// Fix Base Classes
			$ms = match('#(new )(\\\\?)([a-zA-Z0-9-_]+)(.?)#', $nc);
			foreach($ms as $m){
				$match = $m[0];
				$found=false;
				foreach($NS as $nss){
					if(false!==strpos($match, $nss[1]))
						$found=true;
				}
				
				if(!$found){
					$new = $m[1];
					$root = $m[2];
					$class = $m[3];
					$after = $m[4];
					if(trim($root)==''){						
						$nc = str_replace($match, $new. '-BEINGCHANGED-'. $thisns . '_' . $class . $after, $nc);
					}
					else{
						
						$nc = str_replace($match, $new. '-BEINGCHANGED-'. $class.  $after, $nc);
					}
				}
			}		
		}
				
		
		}
		
		$nc = str_replace('-BEINGCHANGED-', '', $nc);
		
		highlight_string($nc);
		
		file_put_contents($file, $nc);
	}
}




// Read Dir and return files
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

function d($str){
	echo '<pre>';
	var_dump($str);
	echo'</pre>';
}

function match($pattern, $from){
	preg_match_all($pattern, $from, $matches);
	$data=array();
	for($i=0;$i<count($matches[0]);$i++){
		for($y=0;$y<count($matches);$y++){
			$data[$i][$y] = $matches[$y][$i];
		}
	}
	return $data;
}
?>