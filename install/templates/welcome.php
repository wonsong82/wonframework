<?php
/**
 * @package Installer/templates
 * @name welcome.php
 * @desc Display welcome page
 * @author Won Song
 */
 
if ($_POST['progress'] < 0)
	exit();
?>

<table>
    <tr>
        <td><h1>Welcome to Webwon WDK. Lets get started.</h1></td>
    </tr>
    <tr>
        <td align="center">
        	<input type="button" value="Start >>" next="requirement" progress="1" onclick="changeHash(this)" />
        </td>
    </tr>
</table>