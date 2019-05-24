<?php
// namespace app\engine;
final class app_engine_Lang{
	
	public $defaultLang;
	public $lang;
	public $langText;
	public $isDefault;
		
	public $langs;
	
	public function __construct(){
		$this->langs = array('en'=>'english');
		$this->defaultLang = 'en';		
	}
	
	public function set($lang){
		$this->lang = isset($this->langs[$lang])? $lang:$this->defaultLang;
		$this->langText = $this->langs[$this->lang];
		$this->isDefault = $this->lang==$this->defaultLang? true : false;
	}		
}
?>