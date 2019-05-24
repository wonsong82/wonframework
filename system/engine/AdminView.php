<?php
namespace app\engine;
class AdminView{
	
	protected $reg;
	protected $name;
	protected $controller;
	protected $model;
	protected $pages=array();
	protected $submenus=array();
	protected $page;
	protected $args;
	protected $pageObj=null;
	protected $rowid=0;
	
	public function __construct($registry){
		$this->reg = $registry; // Registry
		//Get this module name
		$module = lcfirst(str_replace('Admin','',
			str_replace('app\\module\\','',get_class($this))));
		$this->name = $module;
		$this->controller = $this->$module;
		$this->model = $this->$module->model;
		$this->config->thisAdminUrl = $this->config->admin . $this->name . '/';
		$this->page=isset($this->url->args['page'])? $this->url->args['page']:false;
		
		unset($this->url->args['menu']);
		$this->args = $this->url->args;
		
		$this->rowid=isset($this->args['rowid'])?$this->args['rowid']:0;
		$this->construct();
		$this->parsePage();	
	}
	
	public function __get($name){
		return $this->reg->$name;
	}
	
	
	// @ To be overwritten
	// Set up the layouts of the page
	protected function construct(){
	}
	
	protected function getText($key){
		return $this->controller->getText($key);
	}
	
	//
	// Parse the page Object
	protected function parsePage(){
		if(!$this->page && count($this->pages) && count($this->args)==0){
			$this->page = $this->pages[0]['page']->id;			
		}
		foreach($this->pages as $page){
			if($page['display']){
				$this->submenus[] = array(
					'title'=>$page['page']->text,
					'url'=>$this->config->thisAdminUrl.'page='.$page['page']->id.'/',
					'selected'=>$this->page==$page['page']->id?true:false
				);
			}
			if($this->page==$page['page']->id){
				$this->pageObj = $page['page'];
			}
		}
	}
	
	public function render(){
		if($this->pageObj){
			$this->pageObj->render();
		}
	}
	
	//
	// Public Function
	// Return Submenus
	public function getSubmenus(){
		return $this->submenus;		
	}
	
	
	protected function addPage(\app\engine\displayobj\Page $page,$display=true){
		$page->parent = $this;
		$this->pages[] = array('page'=>$page,'display'=>$display);
	}	
	
	
		
	//
	// For Display Objects
	public function __call($func, $args){
		if(strpos('new', $func)==0){
			$ns = '\\app\\engine\\displayobj\\';
			$class = str_replace('new','',$func);
			$id = $args[0];
			require_once $this->config->siteDir . 'system/engine/displayobj/DisplayObject.php';
			require_once $this->config->siteDir . 'system/engine/displayobj/'.$class.'.php';
			$class = $ns.$class;
			return new $class($id, $this->reg);
		}	
	}
	/*
	public function __call($func, $args) {
		return call_user_func_array(array($this->dbObj, $func), $args);
	}
	
		
	protected function newButton($id){
		require_once $this->config->siteDir.'system/engine/displayobj/Button.php';
		return new \app\engine\displayobj\Button($id, $this->reg);
	}	
	protected function newTable($id){
		require_once $this->config->siteDir.'system/engine/displayobj/Table.php';
		return new \app\engine\displayobj\Table($id, $this->reg);
	}
	protected function newTextField($id){
		require_once $this->config->siteDir.'system/engine/displayobj/TextField.php';
		return new \app\engine\displayobj\TextField($id, $this->reg);
	}
	protected function newCheckBox($id){
		require_once $this->config->siteDir.'system/engine/displayobj/CheckBox.php';
		return new \app\engine\displayobj\CheckBox($id, $this->reg);
	}
	protected function newText($id){
		require_once $this->config->siteDir.'system/engine/displayobj/Text.php';
		return new \app\engine\displayobj\Text($id, $this->reg);
	}
	protected function newUploader($id){
		require_once $this->config->siteDir.'system/engine/displayobj/Uploader.php';
		return new \app\engine\displayobj\Uploader($id, $this->reg);
	}*/
	
}
?>