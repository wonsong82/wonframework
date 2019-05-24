<?php
namespace app\engine;
final class Lang{
	
	public $defaultLang = 'en';
	public $lang;
	public $langText;
	public $isDefault;
	
	public $langs=array(
		'en' => 'english',
		'ko' => '한글',		
		'cn' => '中國語',
		'jp' => '日本語'
	);
	
	public function set($lang){
		$this->lang = isset($this->langs[$lang])? $lang:$this->defaultLang;
		$this->langText = $this->langs[$this->lang];
		$this->isDefault = $this->lang==$this->defaultLang? true : false;
	}		
}
?>