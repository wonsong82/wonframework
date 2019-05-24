<?php
// check database
$host = trim($_POST['dbhost']);
$user = trim($_POST['dbuser']);
$pass = trim($_POST['dbpass']);
$db = trim($_POST['dbdb']);


if ($host=='') 
{
	echo '0';
	exit();
}

@$sql = new MySQLi($host, $user, $pass, $db);

if ($sql->connect_error)
{
	echo '0';
	exit();
}


$checkdb = $sql->query("
	SELECT `SCHEMA_NAME` FROM INFORMATION_SCHEMA.SCHEMATA
	WHERE SCHEMA_NAME = '{$db}'
");
if (!$checkdb->num_rows)
{
	echo '0';
	exit();
}

?>