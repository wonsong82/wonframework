<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$permalink = new Permalink();

switch ($_POST['method'])
{
	case 'update' :		
		$permalink->update($_POST['id'], $_POST['key'], $_POST['value']);
		break;
		
	case 'add' :
		$permalink->add($_POST['uri'], $_POST['title'], $_POST['template_path']);
		break;
		
	case 'remove' :
		$permalink->remove($_POST['id']);
		break;
	
	default :
		break;
}
?>
