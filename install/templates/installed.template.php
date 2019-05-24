<?php
require_once '../config.php';
?>
<h1>Your site is already installed!</h1>
<table>
	<tr>    	
    	<td>
            <p>Welcome back, <?=ADMIN_NAME?>.</p>
            
            <p>Your site is already installed. If you would like to reinstall, please delete your configuration file and run the install again.</p>
            <p><a href="<?=SITE_URL?>">Go to site</a> or <a href="<?=ADMIN_URL?>">Go to admin page</a></p>
		</td>
	</tr>
</table>