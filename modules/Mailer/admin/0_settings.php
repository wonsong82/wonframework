<?php
Won::set(new Mailer());
?>

<table id="mailer-settings">
    <tr>
        <td width="90"><label for="smtp_host">SMTP Host</label></td>
        <td width="200"><input type="text" name="smtp_host" id="smtp_host" value="<?=Won::get('Mailer')->smtp_host?>" /></td>
        <td width="20" class="load"></td>
        <td></td>
    </tr>    
    <tr>
        <td><label for="from_name">From (Name)</label></td>
        <td><input type="text" name="from_name" id="from_name" value="<?=Won::get('Mailer')->from_name?>" /></td>
        <td class="load"></td>
    </tr>
    <tr>
        <td><label for="from_email">From (Email)</label></td>
         <td><input type="text" name="from_email" id="from_email" value="<?=Won::get('Mailer')->from_email?>" /></td>
         <td class="load"></td>
    </tr>
   
</table>

<script>
$(function(){	
	// ajax update when key pressed
	$('#mailer-settings input').keyup(function(e){
		var key = $(this).attr('name');
		var value = $(this).val();	
		var loading_target = $(this).parent().next('.load');
				
		load_ajax('<?=Won::get('Config')->this_module?>', 'controller', {
			'method' : 'update',
			'key' : key,
			'value' : value			
		}, loading_target, '#msg');		
		
	});	
});



</script>

