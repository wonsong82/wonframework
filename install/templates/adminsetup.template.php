<?php
include '../includes/timezones.php';
$timezones = get_timezones();


// Define $root
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https://' : 'http://';
$sub = str_replace('/install/templates/adminsetup.template.php', '', $_SERVER['SCRIPT_NAME']);
$root = $protocol . $_SERVER['HTTP_HOST'] . $sub;



?>


<h1>Site &amp; Admin Setup</h1>

<table>
	
    <thead>
    	<td colspan="2">Please provide site's informatoin</td>
    </thead>
	
    <tr>
    	<td width="80" valign="top">
        	<label for="host">Site Url</label>
        </td>
        <td>
        	<input id="host" name="host" type="text" value="<?=$root?>" />
            <p class="alert">Please include 'http://' or https://'</p>
            <p class="alert-noicon">include 'www' if you want 'www.yoursite.com'</p>            
        </td>
    </tr>
    
    <tr>
    	<td><label for="timezone">Time Zone</label></td>
        
        <td>
        	<select name="timezone" id="timezone">
            	<?php foreach ($timezones as $key=>$value) { ?>
                <option value="<?=$key?>"><?=$value?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    
</table>

<table> 
	<thead>
    	<td colspan="2">Please provide full name &amp; desired admin password</td>
    </thead>
    <tr>    	
    	<td width="80">
        	<label for="adminname">Admin Name</label>
        </td>
        <td>
        	<input name="adminname" id="adminname" type="text" value="" />            
        </td>
    </tr>
    
    <tr>
    	<td><label for="password">Password</label></td>
        <td><input name="password" id="password" type="text" value="" /></td>
    </tr>
            
    
       
    <tr>
    	<td colspan="2" align="right">
        	<input type="button" id="check-admin" value="Next >>" onclick="check_admin()" />
        </td>
    </tr>
    
</table>


<script>
	

function check_admin() {		
	$('#loading').html(loading);
	$.ajax({
		url : 'controllers/check.admin.php',
		cache : false,
		type : 'post',
		data : {
			host : $('#host').val(),
			adminname : $('#adminname').val(),
			password : $('#password').val()				
		},
		success : function(data) {
			$('#loading').html('');
				
			if (data=='') {
				hash_change({hashtag:'finish', progress:4});
			}
			
			else {
				$('#msg').html('<p class="error">'+data+'</p>').show().stop(true).delay(3000).fadeOut(300);;
			}
		}
	});
	
}


</script>



