<table>
	<tr><td colspan="2"><h3>Username</h3></td></tr>
    <tr><td><input type="text" id="username" /></td><td width="16" class="load"></td></tr>
        
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Password</h3></td></tr>
    <tr><td><input type="password" id="password1" /></td><td width="16" class="load"></td></tr>
    <tr><td><h3>Repeat Password</h3></td>
    <tr><td><input type="password" id="password2" /></td><td width="16" class="load"></td></tr>
       
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Name</h3></td></tr>
    <tr><td><input type="text" id="name" /></td><td width="16" class="load"></td></tr>
        
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Email</h3></td></tr>
    <tr><td><input type="text" id="email" /></td><td width="16" class="load"></td></tr>
    <tr><td class="error"></td>   
    
     <tr><td>&nbsp;</td></tr>    
     <tr>
     	<td><button id="add">OK</button></td>
        <td class="load"></td>
     </tr>
    
</table>

<script>
$(function(){
	
	// ADD a new user
	$('#add').click(function(){
		
		$('.error').html('');
		if ($('#password1').val() != $('#password2').val())
			$('.error').html('Two passwords do not match');
		
		var param = {
			method : 'add',			
			username : $('#username').val(),
			password : $('#password1').val(),
			name : $('#name').val(),
			email : $('#email').val()
		};		
		var loading_target = $(this).parent().parent().find('.load');		
		
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target, function(data){
			if (data == 'Success'){
				window.location.href = '<?=Won::get('Config')->admin_url . '/' . Won::get('Config')->this_module;?>';
			}
			else{
				$('.error').append('<br/>'+data);
			}
		});
						
	});
});

</script>