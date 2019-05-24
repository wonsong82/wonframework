<?php
require_once '../../../config.php';
Won::set(new File());

$upload = Won::get('File')->upload($_FILES['file'], $_POST['path']);
$data = array();

if (!$upload) {
	
	$data['status'] = 0;
	$data['error'] = Won::get('File')->error;
}
else {
	$data['status'] = 1;
	$data['file'] = $upload;
	$data['error'] = Won::get('File')->error;
}
echo json_encode($data);
?>
