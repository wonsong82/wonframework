<?php
$params =$this->Permalink->parse_get($this->Permalink->params['params']);
if (isset($params['edit']) && is_numeric($params['edit']))
	require $this->Config->this_module_dir.'/admin/events_edit.php';
else
	require $this->Config->this_module_dir.'/admin/events_view.php';
?>