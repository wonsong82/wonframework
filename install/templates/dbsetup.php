<?php
/**
 * @package Installer/templates
 * @name welcome.php
 * @desc Db setup
 * @author Won Song
 */
 
 
// check for the progress
if ($_POST['progress'] < 2)
exit();
?>


<h1>Database Setup</h1>

<table id="dbtable">

	<thead>
    	<td colspan="2">Please provide database information</td>
    </thead>
	
    <tr>    	
    	<td width="80">
        	<label for="dbhost">Host Name</label>
        </td>
        <td>
        	<input name="dbhost" id="dbhost" type="text" value="<?=isset($_POST['db_host'])? $_POST['db_host'] : '';?>" />
        </td>
    </tr>
    
    <tr>
    	<td><label for="dbuser">User Name</label></td>
        <td><input name="dbuser" id="dbuser" type="text" value="<?=isset($_POST['db_user'])? $_POST['db_user'] : '';?>" /></td>
    </tr>
    
    <tr>
    	<td><label for="dbpass">Password</label></td>
        <td><input name="dbpass" id="dbpass" type="text" value="<?=isset($_POST['db_pass'])? $_POST['db_pass'] : '';?>" />
    </tr>
    
    <tr>
    	<td><label for="dbdb">Database</label></td>
        <td><input name="dbdb" id="dbdb" type="text" value="<?=isset($_POST['db_db'])? $_POST['db_db'] : '';?>"/>
    </tr>
    
    <tr>
    	<td><label for="prefix">Prefix</label></td>
        <td><input name="prefix" id="prefix" type="text" value="<?=isset($_POST['db_prefix'])? $_POST['db_prefix'] : 'won_';?>"/>
    </tr>
    
    <tr>
    	<td colspan="2" align="right">
        	<input type="button" id="dbcheck" value="Next >>" onclick="db_check()" />
        </td>
    </tr>
    
</table>


<script>
// when the ok button is clicked, 
// ajax check for the db, when all is good,
// set progress and move on to the next
function db_check() {		
	loading.on();	
	$.ajax({
		url : 'includes/check.db.php',
		cache : false,
		type : 'post',
		data : {
			dbhost : $('#dbhost').val(),
			dbuser : $('#dbuser').val(),
			dbpass : $('#dbpass').val(),
			dbdb : $('#dbdb').val()
		},
		success : function(data){
			loading.off();
			
			// if good
			if (data=='') {
				config.db_host = $('#dbhost').val();
				config.db_user = $('#dbuser').val();
				config.db_pass = $('#dbpass').val();
				config.db_db = $('#dbdb').val();
				config.db_prefix = $('#prefix').val();
				
				changeHash({
					next : 'adminsetup',
					progress : 3
				});
			}
			
			else {
				$('#msg').html('<p class="error">Database information incorrect.</p>').show().stop(true).delay(3000).fadeOut(300);
			}			
		}
	});		
}

</script>




