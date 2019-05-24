<?php
// Get Reqests
$name = strip_tags(trim($_POST['name']));
$email = trim($_POST['email']);
$phone = strip_tags(trim($_POST['phone']));
$subject = strip_tags(trim($_POST['subject']));
$message = $_POST['message'];
$subscribe = $_POST['subscribe']=='yes'? true:false;

// Validate each one of em
if ($name=='')
	die('Your name is empty or invalid.');
if (!$this->Validate->email($email))
	die('Your email is empty or invalid.');
if (!preg_match('/^[0-9-]+$/', $phone))
	die('Your phone number must be in 000-000-0000 format. Please provide your country code as well');
if ($subject=='')
	die('Subject is empty or invalid.');
if ($message=='')
	die('Message is empty or invalid.');
if ($subscribe!='yes' && $subscribe!='no')
	die('Subscribe is unchecked or invalid.');

// Add to DB
$formName = 'Contact Form';
$values = serialize(array(
	'Name'=>$name,
	'E-mail'=>$email,
	'Phone'=>$phone,
	'Subject'=>$subject,
	'Message'=>$message,
	'Subscribe'=>$subscribe
));
$this->Forms->addRecord('Contact Form', $values); 


// Send Notification Mail


echo 'ok';
?>