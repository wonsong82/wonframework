<?php
require_once '../../../config.php';
Won::set(new User());

switch ($_POST['method'])
{
	
	case 'add' :
		Won::get('User')->add_user($_POST['username'], $_POST['password'], $_POST['name'], $_POST['email']);
		if (Won::get('User')->error != '') 
		{
			echo Won::get('User')->error;
		}
		else
		{
			Won::get('User')->add_user_to_default_group($_POST['username']);
			
			echo 'Success';
		}
			
		break;
	
	case 'remove' :
		Won::get('User')->remove_user($_POST['id']);
		break;
	
	
	case 'update_name' :		
		Won::get('User')->update_user_name($_POST['id'], $_POST['val']);
		if (Won::get('User')->error != '')
			echo Won::get('User')->error;		
			
		break;
	
	case 'update_email' :
		Won::get('User')->update_user_email($_POST['id'], $_POST['val']);
		if (Won::get('User')->error != '')
			echo Won::get('User')->error;
		
		break;
	
	case 'update_password' :
		Won::get('User')->update_user_password($_POST['id'], $_POST['val']);
		if (Won::get('User')->error != '')
			echo Won::get('User')->error;
		else
			echo 'Success';
		break;
	
	case 'update_active' :
		Won::get('User')->update_user_active($_POST['id'], $_POST['val']);	
		break;
	
	case 'update_banned' :
		Won::get('User')->update_user_banned($_POST['id'], $_POST['val']);	
		break;
	
	case 'add_to_group' :
		Won::get('User')->add_user_to_group($_POST['uname'], $_POST['gname']);
		break;
		
	case 'remove_from_group' :
		Won::get('User')->remove_user_from_group($_POST['uname'], $_POST['gname']);
		break;		
		
	
	default :
		break;
}
?>
