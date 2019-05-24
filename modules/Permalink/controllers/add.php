<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';
	
$permalink = new Permalink();

$uri = trim($_POST['uri']);
$title = trim($_POST['title']);
$template_path = trim($_POST['template_path']);

if ($uri == '' || $template_path == '')
{
	echo '<span class="error">URI or Template Path cannot be omitted.</span>';
	exit();
}

$permalink->add($uri, $title, $template_path);
?>
