<?php
require_once '../../../config.php';

Won::set(new Mailer());

switch ($_POST['method'])
{
	case 'update' :	
		
		Won::get('Mailer')->update($_POST['key'], $_POST['value']);			
		break;
	
	default :
		break;
}
?>
