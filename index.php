<?php
/**
 * @package Webwon
 * @name index.php
 * @author Won Song
 * @desc Load configs and contents
 */


// Redirect to install for the first time
if (!file_exists('config.php')) 
{
	header('location:install');
	exit();
}

// Load config
require 'config.php';

// set and enable permalink
Won::set(new Permalink());

// if function exists, load functions
is_dir(Won::get('Config')->include_dir) || 
mkdir(Won::get('Config')->include_dir);

if (!Won::get('Permalink')->is_admin)
{
	$includes = opendir(Won::get('Config')->include_dir);
	while (false !== ($file = readdir($includes)))
	{
		if (preg_match('#\.php$#', $file))
			require Won::get('Config')->include_dir . '/' . $file;
	}
}

Won::set(new Template());
Won::get('Template')->printTemplate();
?>