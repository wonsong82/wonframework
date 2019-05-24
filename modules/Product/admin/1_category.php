<?php
Won::set(new Product());
$params = Won::get('Permalink')->parse_get(Won::get('Permalink')->params['params']);
if (isset($params['edit']) && is_numeric($params['edit']))
	require Won::get('Config')->this_module_dir . '/admin/category_edit.php';
else
	require Won::get('Config')->this_module_dir . '/admin/category_view.php';	
?>