<?php
$p=$this->Permalink->parse_get($this->Permalink->params['params']);
if (isset($p['edit']))
	require $this->Config->this_module_dir.'/admin/edit_record.php';
else
	require $this->Config->this_module_dir.'/admin/view_forms.php';
?>

