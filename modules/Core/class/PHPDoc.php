<?php
class PHPDoc
{
	
	
	
	/**
	 * @name PHPDoc()
	 * @desc Parse Doc Comments
	 * @param none	
	 * @return void
	 */
	public function __construct(){}
	
	
	
		
	/**
	 * @name
	 * @desc 
	 * @param
	 * @return
	 */	
	public function public_document($class_name)
	{
		$methods = $this->get_methods($class_name, true);
		$properties = $this->get_properties($class_name, true);	
		$doc = '';
		
		if (count($properties))
		{
			$doc .= '<br/><b>Properties : </b><br/>';
		
			foreach ($properties as $property)
			{				
				$doc .= '<table width="100%"><tr><td>';
				$doc .= '<b>▷</b>' . $property['type'] .' <b> ' .$property['name']. '</b><br/>';
				$doc .= '<i>'.$property['desc']. '</i><br/>';										
				$doc .= '</td></tr></table>';
			}
		}	
		
		if (count($methods))
		{
			$doc .= '<br/><br/><b>Methods : </b><br/>';
					
			foreach ($methods as $method) 
			{			
				$doc .= '<table width="100%"><tr><td>';
				$doc .= '<b>▷ ' .$method['name']. '</b> : '.$method['return'].'<br/>';
				$doc .= '<i>'.$method['desc']. '</i><br/>';
				$doc .= '<br>';
				$doc .= '<b>Parameters: </b><br/>';
				$doc .= nl2br($method['param']) . '<br/';
				$doc .= '</td></tr></table>';			
			}
		}
				
		
		return $doc=='' ? 'No help is available.' : $doc;
	}
	
	
	
	
	/**
	 * @name
	 * @desc 
	 * @param
	 * @return
	 */	
	public function get_property_names($class_name)
	{
		$class = new reflectionClass($class_name);
		$properties = $class->getProperties();
		$return = array();
		foreach ($properties as $property) 
			$return[] = $property->name;
		return $return;
	}
	
	public function get_properties($class_name, $public_only=false)
	{
		$prop_names = $this->get_property_names($class_name);
		$props = array();
		
		if (count($prop_names))
		{
			foreach ($prop_names as $prop_name)
			{				
				$reflect = new ReflectionProperty($class_name, $prop_name);
				$comment = $reflect->getDocComment();
								
				if ($public_only && $reflect->isPrivate()) 
				;
				
				else if ($comment)
				{				
					$prop = array();
					$prop['name'] = $this->parse_value('name', $comment);
					$prop['desc'] = $this->parse_value('desc', $comment);
					$prop['type'] = $this->parse_value('type', $comment);
					
					$props[] = $prop;
				}
			}
		}
		
		return $props;
	}
	
	
	
	public function get_method_names($class_name)
	{
		$class = new reflectionClass($class_name);
		$methods = $class->getMethods();
		$return = array();
		foreach ($methods as $method)
			$return[] = $method->name;
		return $return;
	}
	
	public function get_methods($class_name, $public_only=false)
	{
		$method_names = $this->get_method_names($class_name);
		$methods = array();			
		
		if (count($method_names))
		{
			foreach ($method_names as $method_name)
			{
				$reflect = new ReflectionMethod($class_name, $method_name);
				$comment = $reflect->getDocComment();
				
				if ($public_only && $reflect->isPrivate()) 
				;
				
				else if ($comment)
				{
					$method = array();
					$method['name'] = $this->parse_value('name', $comment);
					$method['desc'] = $this->parse_value('desc', $comment);
					$method['param'] = $this->parse_value('param', $comment);
					$method['return'] = $this->parse_value('return', $comment);
					
					$methods[] = $method;
				}
			}
		}
		
		return $methods;
	}
	
	
	private function parse_value($key, $text)
	{
		preg_match_all('#\* @'.$key.' (.*)?\r\n#', $text, $matches);
				
		if (count($matches[1]))		
			return implode("\n",$matches[1]);
		else 
			return '';		
	}
	
}
?>