<?php
if (!isset($_SESSION['woncms_sessid']) || $_SESSION['woncms_sessid']!='123456789') {
	die ('Cannot call this ajax outside of the site scope');
}
$module = $this->Permalink->params['module'];
$method = $this->Permalink->params['method'];
$args = $_POST['args'];

$out = array('data'=>'');

if (class_exists($module)) {
	if (method_exists($module, $method)) {
		$out['data'] = call_user_func_array(array($this->$module,$method), $args);
	}
	else {
		$out['data']=$module.'->'.$method.' method is not accessible';
	}
}
else {
	$out['data']=$module.' module is not accessible';
}
echo json_encode($out);
?>