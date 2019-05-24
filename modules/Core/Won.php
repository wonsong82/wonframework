<?php
class Won
{
	public static $classes = array();
	
	public static function set($arg)
	{
		if (!isset(self::$classes[get_class($arg)]))
			self::$classes[get_class($arg)] = $arg;							
		
		return self::$classes[get_class($arg)];	
	}
		
	public static function get($arg)
	{
		return 
			array_key_exists($arg, self::$classes)?
			
			self::$classes[$arg] : false;		
	}
		
	
}
?>