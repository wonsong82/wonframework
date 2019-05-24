<?php

$host = rtrim(trim($_POST['host']), '/');
$adminname = trim($_POST['adminname']);
$adminpass = trim($_POST['password']);

// check host
if (!preg_match('#^http(s?)://#', $host))
{	
	echo 'Site Host is missing or incorrect.';
	exit();
}

// check adminname or password
if ($adminname=='' || $adminpass=='')
{
	echo 'Admin Name or Password is missing.';
	exit();
}

// check password
if (!preg_match('#^[a-z0-9\_\-\/\?\!\-\_]{3}+$#i', $adminpass))
{
	echo 'Invalid password format';
	exit();
}

?>
