<?php
// Version : 3.0
set_time_limit(0);


// Install
if (!file_exists(dirname(__FILE__).'/config.php')) {
	header('location:install');
	exit();
}

// Load Engines
foreach (glob(dirname(__FILE__).'/system/engine/*.php') as $startEngine) {
	require_once $startEngine;
}

// Start the Registry
$registry = new Registry();

// Load Config
require_once dirname(__FILE__).'/config.php';


// Initialize
date_default_timezone_set($registry->config->timezone); // Set Timezone

$registry->lib->import('helper.magicQuotesFix'); // Run Fixes
$registry->lib->import('helper.registerGlobalsFix'); //

$registry->session->start(); // Start the Session

$registry->db;

// Output the Http Response
$registry->template->output();
?>