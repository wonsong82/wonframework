<?php
/**
 * @package Installer
 * @name index.php
 * @author Won Song
 */

$content = file_exists('../config.php')? 'installed.php' : 'install.php';

require "./templates/{$content}";





/*

// check for the config file
$installed = file_exists('../config.php')? true : false;

?>
<!doctype html>

<html>

<head>
	<title>Webwon WDK(Web Development Kit) Installation</title>
    <link rel="stylesheet" href="style.css" />
	<script src="scripts/jquery.js"></script>
    
	<?php if (!$installed) { ?>
	<script src="script.js"></script>
    <?php } ?>
    
</head>

<body>
	
   <h1>Webwon WDK(Web Development Kit) Installation</h1>
   
   
   <div id="content">
   <?php if ($installed) require_once 'templates/installed.template.php'; ?>   			
   </div>
   
   
	<div id="msg"></div>
    <div id="loading"></div>
	
</body>


</html>

*/