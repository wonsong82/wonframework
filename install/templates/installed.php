<?php
/**
 * @package Installer/templates
 * @name installed.php
 * @desc Display this template if installation is already finished.
 * @author Won Song
 */

require '../config.php';
?>
<!doctype html>

<html>

<head>
	<title>Webwon WDK(Web Development Kit) Installation</title>
    <link rel="stylesheet" href="style.css" />	 
</head>

<body>	
	<h1>Webwon WDK(Web Development Kit) Installation</h1>  
	<div id="content">
		<h1>Your site is already installed!</h1>
		<table>
			<tr>    	
    			<td>
            		<p>Welcome back.</p>            
            		<p>Your site is already installed. If you would like to reinstall, please delete your configuration file and run the install again.</p>
            		<p><a href="<?=Won::get('Config')->site_url?>">Go to site</a> or <a href="<?=Won::get('Config')->admin_url?>">Go to admin page</a></p>
				</td>
			</tr>
		</table>
	</div>  
</body>

</html>