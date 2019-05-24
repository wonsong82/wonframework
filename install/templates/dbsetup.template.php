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
        	<input name="dbhost" id="dbhost" type="text" value="" />
        </td>
    </tr>
    
    <tr>
    	<td><label for="dbuser">User Name</label></td>
        <td><input name="dbuser" id="dbuser" type="text" value="" /></td>
    </tr>
    
    <tr>
    	<td><label for="dbpass">Password</label></td>
        <td><input name="dbpass" id="dbpass" type="text" value="" />
    </tr>
    
    <tr>
    	<td><label for="dbdb">Database</label></td>
        <td><input name="dbdb" id="dbdb" type="text" value=""/>
    </tr>
    
    <tr>
    	<td><label for="prefix">Prefix</label></td>
        <td><input name="prefix" id="prefix" type="text" value="won_"/>
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
	// check the database connection when values change	
	$('#loading').html(loading);
	$.ajax({
		url : 'controllers/check.db.php',
		cache : false,
		type : 'post',
		data : {
			dbhost : $('#dbhost').val(),
			dbuser : $('#dbuser').val(),
			dbpass : $('#dbpass').val(),
			dbdb : $('#dbdb').val()
		},
		success : function(data){
			$('#loading').html('');
			// success :
			// display next form
			
			// if good			
			if (data=='') {
				hash_change({hashtag:'adminsetup', progress:3});					
			}
			else {
				$('#msg').html('<p class="error">Database information incorrect.</p>').show().stop(true).delay(3000).fadeOut(300);
			}
		}
	});		
}

</script>




