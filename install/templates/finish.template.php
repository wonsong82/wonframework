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
	
	$('#loading').html(loading);	
	
	var param = {};	
	param.db_host = $('#dbhost').val();
	param.db_user = $('#dbuser').val();
	param.db_pass = $('#dbpass').val();
	param.db_db = $('#dbdb').val();
	param.db_prefix = $('#prefix').val();
	param.site_url = $('#host').val();
	param.admin_name = $('#adminname').val();
	param.admin_pass = $('#password').val();
	param.time_zone = $('#timezone').val();
		
	$.ajax({
		url : 'controllers/create.config.php',
		cache : false,
		type : 'post',
		data : param,
		success : function(data) {
			$('#loading').html('');			
			
			$('#finish-head').html('Setup Completed');
			$('#finish-msg').html('Congratulation!<br/>Installation completed.<br/>Click <a href="'+data+'/admin">here</a> to go to the admin page.');
			
		}
	});	
	
}


</script>