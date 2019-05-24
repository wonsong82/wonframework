<?php
// namespace app\engine\displayobj;
class app_engine_displayobj_Table extends app_engine_displayobj_DisplayObject{
	
	public $order='';
	public $reverseOrder = false;
	public $rows=array();
	
	// Link Data, Must be Multi Array
	public function linkData($multiArray, $reverse=false){
		$this->reverseOrder = $reverse;
		if($this->reverseOrder){
			$newArr = array();
			for($i=count($multiArray)-1;$i>=0;$i--){
				$newArr[] = $multiArray[$i];
			}
			$multiArray = $newArr;		
		}
		
		foreach($multiArray as $row){
			require_once $this->reg->config->siteDir.'system/engine/displayobj/Row.php';
			$rowObjClass = $this->ns['displayobj'] . 'Row';
			$rowObj = new $rowObjClass($row['id'], $this->reg);
			foreach($row as $key=>$val){
				if($key!='id')
					$rowObj->addCol($key, $val);
			}
			$rowObj->parent = $this;
			$this->rows[] = $rowObj;
		}
	}
	
	//
	// Set Order Module.Table Name
	public function order($module, $table){
		$this->order = $module.','.$table;
	}
	
	// @override
	// Add this Obj to each rows
	public function addChild($obj){
		foreach($this->rows as $rowObj){
			$obj = clone $obj;
			$obj->parent = $rowObj;
			$rowObj->addChild($obj);
		}		
	}
	
	// @override
	//
	public function render(){
		$args['rows'] = $this->rows;
		$args['order'] = $this->order;
		$args['reverse'] = $this->reverseOrder;
		
		parent::render($args);
	}
}
?>