<?php

$host = rtrim(trim($_POST['host']), '/');
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$password2 = trim($_POST['password2']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);

// check host
if (!preg_match('#^http(s?)://#', $host))
{	
	echo 'Site Host is missing or incorrect.';
	exit();
}

// check username
if ($username=='' || !preg_match('#^[a-zA-Z0-9]{5,20}$#', $username))
{
	echo 'Username must be letters and digits only, 5-20 characters.';
	exit();
}

if ($password=='' || $password2=='' || !preg_match('#^[a-zA-Z0-9\_\-\/\?\!\-\_]{3,}$#', $password))
{	
	echo 'Invalid Password';
	exit();
}

if ($password!=$password2)
{
	echo 'Passwords don\'t match';
	exit();
}

if ($name == '')
{
	echo 'Invalid Name';
	exit();
}

if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#', $email))
{
	echo 'Invalid Email';
	exit();
}
?>
