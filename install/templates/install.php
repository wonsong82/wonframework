<?php
/**
 * @package Installer/templates
 * @name install.php
 * @desc Installer container html, with script.js, it will load appropriate templates into #content
 * @author Won Song
 */
?>
<!doctype html>

<html>

<head>
	<title>Webwon WDK(Web Development Kit) Installation</title>
	<link rel="stylesheet" href="style.css" />
	<script src="scripts/jquery.js"></script>
    <script src="scripts/config.js"></script>
	<script src="script.js"></script>    
</head>

<body>	
	<h1>Webwon WDK(Web Development Kit) Installation</h1>  
	<div id="content"><!-- templates loaded via ajax --></div> 
    <div id="msg"><!-- any messagegs --></div>
	<div id="loading"><!-- loading animation--></div>	
</body>


</html>