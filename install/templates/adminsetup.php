<?php
/**
 * @package Installer/templates
 * @name adminsetup.php
 * @desc Admin Setup
 * @author Won Song
 */


// check for the progress
if ($_POST['progress'] < 3)
exit();


// load timezone
require '../includes/timezones.php';
$timezones = get_timezones(); 


// Define $root
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
$sub = str_replace('/install/templates/adminsetup.php', '', $_SERVER['SCRIPT_NAME']);
$site_url = $protocol . $_SERVER['HTTP_HOST'] . $sub;
?>


<h1>Site &amp; Admin Setup</h1>

<table class="admintable">
	
    <thead>
    	<td colspan="2">Please provide site's informatoin</td>
    </thead>
	
    <tr>
    	<td width="80" valign="top">
        	<label for="siteurl">Site Url</label>
        </td>
        <td>
        	<input id="siteurl" name="siteurl" type="text" value="<?=isset($_POST['site_url'])? $_POST['site_url'] : $site_url?>" />
            <p class="alert">Please include 'http://' or https://'</p>
            <p class="alert-noicon">include 'www' if you want 'www.yoursite.com'</p>            
        </td>
    </tr>
    
    <tr>
    	<td><label for="timezone">Time Zone</label></td>
        
        <td>
        	<select name="timezone" id="timezone">
            	<?php foreach ($timezones as $key=>$value) { ?>
                <?php $selected = isset($_POST['time_zone']) && $_POST['time_zone']==$key ? ' selected="selected"' : ''; ?>
                <option value="<?=$key?>"<?=$selected?>><?=$value?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
</table>

<table class="admintable"> 
	<thead>
    	<td colspan="2">Please provide desired Administrator info.</td>
    </thead>
    <tr>    	
    	<td width="80">
        	<label for="username">Username</label>
        </td>
        <td>
        	<input name="username" id="username" type="text" value="<?=isset($_POST['username'])?$_POST['username']:''?>" />            
        </td>
    </tr>
    
    <tr>
    	<td><label for="password">Password</label></td>
        <td><input name="password" id="password" type="password" value="" /></td>
    </tr>
    
    <tr>
    	<td><label for="password2">Repeat Pwd</label></td>
        <td><input name="password2" id="password2" type="password" value="" /></td>
    </tr>   
    
    <tr><td>&nbsp;</td></tr>
    
     <tr>
    	<td><label for="name">Full Name</label></td>
        <td><input name="name" id="name" type="text" value="<?=isset($_POST['name'])?$_POST['name']:''?>" /></td>
    </tr>   
    
    <tr>
    	<td><label for="email">Email</label></td>
        <td><input name="email" id="email" type="text" value="<?=isset($_POST['email'])?$_POST['email']:''?>" /></td>
    </tr> 
       
    <tr>
    	<td colspan="2" align="right">
        	<input type="button" id="check-admin" value="Next >>" onclick="check_admin()" />
        </td>
    </tr>
    
</table>

<script>
function check_admin() {
	
	// start loading while checking admin values		
	loading.on();
	
	// check admin values
	$.ajax({
		url : 'includes/check.admin.php',
		cache : false,
		type : 'post',
		data : {
			host : $('#siteurl').val(),
			username : $('#username').val(),
			password : $('#password').val(),
			password2 : $('#password2').val(),
			name : $('#name').val(),
			email : $('#email').val()				
		},
		success : function(data) {
			loading.off();
			
			// if good	
			if (data=='') {
				
				// write to config object
				config.site_url = $('#siteurl').val();
				config.time_zone = $('#timezone').val();
				config.admin_username = $('#username').val();
				config.admin_password = $('#password').val();	
				config.admin_name = $('#name').val();
				config.admin_email = $('#email').val();			
				
				// change hash and move on to next
				changeHash({
					next:'finish', 
					progress:4
				});
			}
			
			else {
				$('#msg').html('<p class="error">'+data+'</p>').show().stop(true).delay(3000).fadeOut(300);;
			}
		}
	});	
}
</script>
