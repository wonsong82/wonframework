<?php
require_once '../../../config.php';

Won::set(new Settings());

switch ($_POST['method'])
{
	case 'set_logo' :
		
		echo Won::get('Settings')->setSetting('Core','siteLogo',$_POST['logo']);
		
		break;	
	
	
	default :
		break;
}
?>
