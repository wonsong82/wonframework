<?php
$id = $params['password'];
?>

<table>
	<tr><td><h3>Type New Password</h3></td>
    <tr><td><input id="password1" type="password1" /></td></tr>
    <tr><td><h3>Repeat New Password</h3></td>
    <tr><td><input id="password2" type="password2" /></td></tr>
    <tr><td>&nbsp;</td></tr>
    
    <tr><td class="error"></td></tr>
    
    <tr><td><button id="submit">Change</button></td></tr>
    <tr><td class="load"></td>
</table>

<script>
$(function(){
	$('#submit').click(function(){
		
		$('.error').html('');
		
		if ($('#password1').val() != $('#password2').val())
		{
			$('.error').html('Both passowrds do not match<br/>');
			return false;
		}
		
		var param = {
			method : 'update_password',			
			id : <?=$id?>,
			val : $('#password1').val()
		};		
		var loading_target = $('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target, function(data){
			if (data == 'Success')
			{
				window.location.href = '<?=Won::get('Config')->admin_url . '/' . Won::get('Config')->this_module . '/' . Won::get('Config')->this_module_page . '/edit=' . $id;?>';
			}
			else
			{
				$('.error').append(data);
			}
		});			
	});
});
</script>