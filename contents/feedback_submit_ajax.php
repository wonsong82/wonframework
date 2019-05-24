<?php
// Get Reqests
$name = strip_tags(trim($_POST['name']));
$feedback = strip_tags(trim($_POST['feedback']));
$display = $_POST['display']=='yes'? true:false;

// Validate each one of em
if ($name=='')
	die('Your name is empty or invalid.');
if ($feedback=='')
	die('Message is empty or invalid.');

// Add to DB
$values = serialize(array(
	'Name'=>$name,
	'Feedback'=>$feedback,
	'Display'=>$display
));
$this->Forms->addRecord('Feedback Form', $values); 


// Send Notification Mail


echo 'ok';
?>