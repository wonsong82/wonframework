<?php
// Interface

// namespace app\engine;
class app_engine_AdminView{
	
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
	
	public function __construct($reg){
		$this->reg = $reg; // Registry
		
		//Get this module name
		$module = str_replace('Admin','', str_replace($this->reg->ns['module'], '', get_class($this)));
		$module[0] = strtolower($module[0]);
		
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
	
	
	protected function addPage($page,$display=true){
		$page->parent = $this;
		$this->pages[] = array('page'=>$page,'display'=>$display);
	}	
	
	
		
	//
	// For Display Objects
	public function __call($func, $args){
		if(strpos('new', $func)==0){
			$class = str_replace('new','',$func);
			
			$id = $args[0];
			require_once $this->config->siteDir . 'system/engine/displayobj/DisplayObject.php';
			require_once $this->config->siteDir . 'system/engine/displayobj/'.$class.'.php';
			
			$class = $this->reg->ns['displayobj'] .$class;
			return new $class($id, $this->reg);
		}	
	}
	
	
}
?>