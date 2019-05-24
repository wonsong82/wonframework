<?php
namespace app\engine\displayobj;
class Table extends DisplayObject{
	
	public $order='';
	public $rows=array();
	
	// Link Data, Must be Multi Array
	public function linkData($multiArray){
		foreach($multiArray as $row){
			require_once $this->reg->config->siteDir.'system/engine/displayobj/Row.php';
			$rowObj = new \app\engine\displayobj\Row($row['id'], $this->reg);
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
		
		parent::render($args);
	}
}
?>