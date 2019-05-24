<?php
final class ClassDoc {
	
	public function classInfo($class) {
		
		if (gettype($class)=='array') {
			$name = get_class($class[0]);
		} 
		else {
			$name = get_class($class);
			$class = array($class);
		}
		
		$name = get_class($class[0]);
		$constants = array();
		$properties = array();
		$methods = array();		
		
		foreach ($class as $className) {
			$ref = new ReflectionClass($className);
			
			
			foreach ($ref->getConstants() as $constant) {
				$constants[] = '$this->'.$constant->getName().';';
			}		
			
			foreach ($ref->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
				$properties[] = '$this->'.$property->getName().';';
			}
			
			foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if (preg_match('/^__|^help$/',$method->getName())) continue;
				$params = array();
				foreach ($method->getParameters() as $param) {
					if ($param->isOptional()) {
						$val = $param->getDefaultValue();
						if (is_bool($val)) {
							$val = $val? 'true' : 'false';
							$params[] = '$'.$param->getName().'='.$val;
						}
						elseif (is_string($val))
							$params[] = '$'.$param->getName().'="'.htmlspecialchars($val).'"';
						
						else
							$params[] = '$'.$param->getName().'='.$val;				
					}
					else {
						$params[] = '$'.$param->getName();
					}			
				}
				
				$methods[] = '$this->'.$method->getName().'('.implode(', ',$params).');';			
			}			
		}
		
		$methods = array_unique($methods);
		$constants = array_unique($constants);
		$properties = array_unique($properties);
		
		
		$msg =  '<b>Classname : '. $name .'</b><br/><br/>';
		$msg .= '<b>Constants</b><br/>';
		$msg .= count($constants)? implode('<br/>'.$constants).'<br/><br/>' : 'none'.'<br/><br/>';
		$msg .= '<b>Properties</b><br/>';
		$msg .= count($properties)? implode('<br/>',$properties).'<br/><br/>' : 'none'.'<br/><br/>'; 
		$msg .= '<b>Methods</b><br/>';
		$msg .= count($methods)? implode('<br/>',$methods).'<br/><br/>' : 'none<br/><br/>';
		
		return $msg;
	}
}
?>