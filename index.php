<?php
/**
 * @package Webwon
 * @name index.php
 * @author Won Song
 * @desc Load configs and contents
 */


// Redirect to install for the first time
if (!file_exists('./config.php')) 
{
	header('location:install');
	exit();
}

// Load config
require './config.php';
$page = new Permalink();

// if function exists, load functions
is_dir(INCLUDE_DIR) || mkdir(INCLUDE_DIR);
$includes = opendir(INCLUDE_DIR);
while (false !== ($file = readdir($includes)))
{
	if (preg_match('#\.php$#', $file))
		require INCLUDE_DIR . '/' . $file;
}

require CONTENT_DIR . '/' . $page->template;
?>