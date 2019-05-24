<?php
require_once '../../../config.php';
Won::set(new Forms());

switch ($_POST['method'])
{
	case 'update_values' :
		
		$id= (int)$_POST['record_id'];
		$values = $_POST;
		unset($values['method']);
		unset($values['record_id']);
		$data = array();
		foreach ($values as $key=>$val) {
			if (preg_match('/yes|no/',$val)) {
				$data[$key]= $val=='yes'? true:false;
			} 
			else
				$data[$key]= $val;
		}
		$data = serialize($data);
		echo Won::get('Forms')->updateRecord($id, $data);
	
	break;
	
	default :
		break;
}
?>
