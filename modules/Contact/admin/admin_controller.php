<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$contact = new Contact();

switch ($_POST['method'])
{
	case 'update' :	
		
		if (!$contact->update($_POST['key'], $_POST['value']))
			echo '<span class="error">' . $contact->error . '</span>';
		break;
	
	default :
		break;
}
?>
