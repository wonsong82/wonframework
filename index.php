<?php

// if config does not exists, go to install
if (!file_exists('config.php'))
	header('location:install');
	
// include configs and initial settings
include_once 'config.php';

$page = new Permalink();


// if function exists, load functions
if (file_exists(CONTENT_DIR . '/includes/functions.php'))
	include_once CONTENT_DIR . '/includes/functions.php';
	
include_once CONTENT_DIR . '/' . $page->template;


?>