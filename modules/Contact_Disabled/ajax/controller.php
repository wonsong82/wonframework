<?php
require_once '../../../config.php';

Won::set(new Contact());

switch ($_POST['method'])
{
	case 'update' :	
		
		if (!Won::get('Contact')->update($_POST['key'], $_POST['value']))
			echo '<span class="error">' . Won::get('Contact')->error . '</span>';
		break;
	
	default :
		break;
}
?>
