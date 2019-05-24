<?php
class ContentElements
{
	public $elements = array();
	
	public function __construct() 
	{
		
		
	}
	
	private function textElement()
	{
		
	}
}

class TextElement
{
	public $id;
	public $page_id;	
	public $value;
	
	public function set_value($value)
	{
		$this->value = Won::get('DB')->sql->real_escape_string(trim(strip_tags($value)));
	}
	
	public function get_value($value)
	{
		return nl2br($this->value);
	}	
}
?>