<?php
require_once '../../../config.php';

Won::set(new Permalink());

switch ($_POST['method'])
{
	case 'update' :		
		Won::get('Permalink')->update($_POST['id'], $_POST['key'], $_POST['value']);
		break;
		
	case 'add' :
		Won::get('Permalink')->add($_POST['uri'], $_POST['title'], $_POST['template_path']);
		break;
		
	case 'remove' :
		Won::get('Permalink')->remove($_POST['id']);
		break;
	
	default :
		break;
}
?>
