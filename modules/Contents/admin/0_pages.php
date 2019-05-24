<?php
Won::set(new Contents());
$params = Won::get('Permalink')->parse_get(Won::get('Permalink')->params['params']);
$settings = Won::get('Contents')->settings;

if (isset($params['edit']) && $params['edit'] == 'page')
	require Won::get('Config')->this_module_dir . '/admin/edit_page.php';

else if (isset($params['edit']) && $params['edit'] == 'element')
	require Won::get('Config')->this_module_dir . '/admin/edit_element.php';

else
	require Won::get('Config')->this_module_dir . '/admin/view_pages.php';
	
?>

