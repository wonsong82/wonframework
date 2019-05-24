<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$contact = new Contact();

$key = $_POST['key'];
$value = $sql->real_escape_string(htmlspecialchars(trim($_POST['value'])));

if (!$contact->validate($key, $value)) 
{
	echo '<span class="error">'. $contact->error . '</span>';
	exit();
}

$contact->update($key, $value);
?>
