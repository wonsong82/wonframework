<?php
// Name : Admin
// Desc : All about admin controls

// namespace app\engine;
final class app_engine_Admin{
		
	public $menu;
	public $file = null;
	public $adminObj = null;
	public $isStatic = false;
	
	private $reg;
	private $menus;
	private $submenus;
		
	public function __construct($reg){
		$this->reg = $reg;
		$this->menus = array();
		$this->submenus = array();
		$this->menu = $this->url->args['menu'];		
	}
	
	public function __get($name){
		return $this->reg->$name;
	}
	
	public function addMenu($module, $displayName, $permissions){
		$permissions = explode(',',$permissions);
		foreach($permissions as $permission){
			if($this->user->isMemberOf(trim($permission))){
				$this->menus[] = array('name'=>$module, 'title'=>$displayName);
				continue;
			}
		}
	}
	
	public function getMenus(){
		return $this->menus;
	}
	
	public function getSubmenus(){
		return $this->submenus;
	}
	
	public function inMenus($name){
		$inMenu=false;
		foreach ($this->menus as $menu){
			if($menu['name']==$name)
				$inMenu = true;
		}
		return $inMenu;
	}
	
	public function parseMenu(){
		if(count($this->menus)&&$this->menu==''){ // If missing menu, set the menu as the first one
			$this->menu = $this->menus[0]['name'];			
		}
		// check for files first
		foreach(glob($this->config->adminDir . '*.php') as $file){
			$fileBasename = basename($file,'.php');
			if($this->menu == $fileBasename){
				$this->file = $file;
				$this->isStatic = true;
				return true;
			}			
		}
		
		// check for module if not file
		if(file_exists($this->config->moduleDir.$this->menu.'/admin.php')){
			$this->file = $this->config->moduleDir.$this->menu.'/admin.php';
			require $this->file;
			
			$adminClassName = $this->menu;
			$adminClassName[0] = strtoupper($adminClassName[0]);
			$adminClassName = $this->reg->ns['module'] . $adminClassName . 'Admin';
			$this->adminObj = new $adminClassName($this->reg);
			$this->submenus = $this->adminObj->getSubmenus();
		}
		return true;
	}
	
	public function renderPage(){
		if($this->isStatic){
			require $this->file;
			return true;
		}
		// check for module if not file
		if($this->adminObj){
			$this->adminObj->render();
			return true;
		}
		// If not module throw non-found error
		echo '<p class="error">Invalid URL.</p>';
		
	}
}
?>