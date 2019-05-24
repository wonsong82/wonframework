<?php
// Name : DisplayObject
// Desc : Controls Default Views of Elements
namespace app\engine\displayobj;
class DisplayObject{
	
	public $id;// ID of this Object, Usually Name
	public $text;// Text, Usually Display Text for this control
	public $desc;// Description
	
	public $parent; // If defined, parent of this Object
	public $childs; // Chidrens of this Object
	
	public $enabled = true; // Enable & Disable
	public $value; // Default Value
	
	public $message; // Alert the message when action is succeeded
	public $css;
		
	protected $reg; // Registry
	protected $className;
	
	protected $action;
	protected $actionArgs;
	protected $redirect;
	protected $redirectArgs;
	
	//
	// Constructor
	public function __construct($id, $reg){
		$this->id = $id;
		$this->text = $id;
		$this->reg = $reg;
		$this->desc = '';
		$this->childs = array();
		$this->className = str_replace('app\\engine\\displayobj\\','',get_class($this));
		$this->action=$this->actionArgs=$this->redirect=$this->redirectArgs='';
	}
	
	
	public function __get($key){
		return $this->reg->$key;
	}
	
	//
	// This is for override
	public function linkData($data){
		$this->value = $data;
	}
	
	// 
	// Renders this Object to Page 
	public function render(){
				
		$action = $this->action;
		if($this->action!='')
			$actionParams=$this->actionParams();
		
		$redirect = $this->redirect;
		if($this->redirect!='')
			$redirect='"'.$this->redirect.'"'.$this->redirectParams();
		
		// Default Variables
		$id = $this->id;
		$text = $this->text;
		$desc = $this->desc;
		$disabled = $this->enabled? '':' disabled="disabled"';
		$value = $this->value;
		
		$reg = $this->reg;
		$childs = $this->childs;;
		$parent = $this->parent;
		
		$css = $this->css;	
		$message = $this->message;
		
		// Additional Variables
		$args = func_get_args();
		if(count($args)){
			$args = $args[0];
			foreach($args as $argKey=>$argVal)
				$$argKey = $argVal;		}
		
		require $this->reg->config->siteDir.'system/engine/displayobj/view/'.$this->className.'.php';
	}
	
	//
	// AddChild
	public function addChild($displayObj){
		$displayObj->parent = $this;
		$this->childs[] = $displayObj;
	}
	
	//
	// Set Action
	public function action($action, $args=''){
		$action=explode('.',$action);
		foreach($action as &$actionRow) trim($actionRow);
		if(count($action)<2){
			trigger_error($this->id.": Invalid Action Info");
			return false;
		}
		$this->action = $action;
		$this->actionArgs = $args;
	}
	
	//
	// Get Action Params
	protected function actionParams(){
		$params=$this->actionArgs;
		if($params!=''){
			$params=explode(',',$params);
			$parr=array();
			foreach($params as $p){
				$p=trim($p);
				if(strstr($p,'parent.')){
					$attr=str_replace('parent.','',$p);
					$p=str_replace('parent.','parent().',$p);
					$p=str_replace($attr,'',$p);
					$p='$(this).'.$p.'attr("'.$attr.'")';
					$parr[]=$p;
				}
				elseif(strstr($p,'#')){
					$p=str_replace('#','',$p);
					$parr[]='$("#'.$p.'").val()';
				}
				else{
					$parr[]='"'.str_replace('@','',$p).'"';
				}
			}
			$params='['.implode(',',$parr).']';				
		}
		else{
			$params='[]';
		}
		return $params;
	}
	
	//
	// Set Redirect
	public function redirect($pagePath, $args=''){
		$path=explode('.',$pagePath);
		foreach($path as &$pathRow) trim($pathRow);
		if(count($path)<2){
			trigger_error($this->id.": Invalid PagePath Info");
			return false;
		}
		$this->redirect = $this->reg->config->admin.$path[0].'/page='.$path[1];
		$this->redirectArgs = $args;
	}
	
	// Get Redirect Params
	protected function redirectParams(){
		$params=$this->redirectArgs;
		if($params!=''){
			$params=explode(',',$params);
			$parr=array();
			foreach($params as $p){
				$p=trim($p);
				if(strstr($p,'parent.')){
					$attr=str_replace('parent.','',$p);
					$p=str_replace('parent.','parent().',$p);
					$p=str_replace($attr,'',$p);
					$p='$(this).'.$p.'attr("'.$attr.'")';
					$parr[]='"'.$attr.'="+'.$p;
				}
				elseif(strstr($p,'=')){
					$parr[]='"'.$p.'"';
				}
				else{
					$parr[]='"'.$p.'="+$("#'.$p.'").val()';
				}
			}
			$params='+"&"+'.implode('+"&"+',$parr);				
		}
		return $params;
	}
}
?>