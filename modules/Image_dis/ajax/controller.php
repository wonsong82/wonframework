<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$image = new Image();

switch ($_POST['method'])
{
	case 'update' :	
		
		if (!$contact->update($_POST['key'], $_POST['value']))
			echo '<span class="error">' . $contact->error . '</span>';
		break;
		
	case 'add' :
	
		if (!isset($_FILES['won-image-uploaded-file']) )
		{
			echo '<span class="error">File was not uploaded.</span>';
			break;
		}
		else 
		{
			$image->add($_FILES['won-image-uploaded-file'], 'gallery', 1);
			header('location:' . $_POST['url']);
		}
				
	
	break;
	
	default :
		break;
		
}
?>
