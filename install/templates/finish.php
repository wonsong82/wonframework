<?php
/**
 * @package Installer/templates
 * @name finish.php
 * @desc Finishing and write config
 * @author Won Song
 */


// check for the progress
if ($_POST['progress'] < 4)
exit();

?>

<h1>Finish Setup</h1>

<table>
	<thead>
    	<td id="finish-head">Config creation</td>
    </thead>
	<tr>
        <td width="300" id="finish-msg">Click the finish button to create the config file.</td>       
    </tr>
    <tr>
    	 <td align="right"><input type="button" id="finish-btn" value="Finish" onclick="create_config(this)" /></td>
    </tr>
</table>

<script>

function create_config(e) {
	
	// remove this button
	$(e).remove();
	
	// turn on the loading
	loading.on();
				
	$.ajax({
		url : 'includes/create.config.php',
		cache : false,
		type : 'post',
		data : config,
		success : function(data) {
			loading.off();	
			
			$('#finish-head').html('Setup Completed');
			$('#finish-msg').html('Congratulation!<br/>Installation completed.<br/>Click <a href="'+data+'/admin">here</a> to go to the admin page.');
			
		}
	});	
	
}


</script>