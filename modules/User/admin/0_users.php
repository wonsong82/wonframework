<?php
Won::set(new User());
$params = Won::get('Permalink')->parse_get(Won::get('Permalink')->params['params']);

if (isset($params['edit']) && is_numeric ($params['edit']))
	require Won::get('Config')->this_module_dir . '/admin/user_edit.php';

else if (isset($params['add']) && is_numeric ($params['add']))
	require Won::get('Config')->this_module_dir . '/admin/user_add.php';
	
else if (isset($params['password']) && is_numeric ($params['password']))
	require Won::get('Config')->this_module_dir . '/admin/user_password.php';

else
	require Won::get('Config')->this_module_dir . '/admin/user_view.php';
	
?>

