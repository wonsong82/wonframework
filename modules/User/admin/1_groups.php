<?php
Won::set(new User());
$params = Won::get('Permalink')->parse_get(Won::get('Permalink')->params['params']);
if (isset($params['edit']) && is_numeric($params['edit']))
	require Won::get('Config')->this_module_dir . '/admin/group_edit.php';
else
	require Won::get('Config')->this_module_dir . '/admin/group_view.php';
?>