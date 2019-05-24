<?php
// Name : Url Model
// Desc : Url Model
namespace app\module;
final class UrlModel extends \app\engine\Model{
		
	//
	// DB Structure
	public function __construct($registry){
		parent::__construct($registry);
		
		$field = $this->table('url')->field('uri', 'text');
		$field->regex = '#^[a-zA-Z0-9\/\-\_$]*$#';
		$field->key = 'unique';
		
		$field = $this->table('url')->field('template', 'text');
		$field->regex = '#[a-zA-Z0-9.-_]+\.php$#';
		$field->key = 'index';		
	}
}
?>