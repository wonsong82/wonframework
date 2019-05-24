<?php
/**
 * @package Webwon
 * @name index.php
 * @author Won Song
 * @desc Load configs and contents
 */


// Redirect to install for the first time
file_exists('./config.php') || header('location:install');

// Load config
require './config.php';
$page = new Permalink();

// if function exists, load functions
$includes = opendir(CONTENT_DIR . '/includes');
while (false !== ($file = readdir($includes)))
{
	if (preg_match('#\.php$#', $file))
		require CONTENT_DIR . '/includes/' . $file;
}

require CONTENT_DIR . '/' . $page->template;
?>