<?php
$id = $params['edit'];
$group = Won::get('User')->get_group($id);
?>

<table id="user-edit">
	<tr>
    	<td colspan="2"><b>Group Name</b></td>        
    </tr>
    <tr>
    	<td><input id="name" value="<?=htmlspecialchars($group['name'])?>"/></td>
        <td width="16" class="load"></td>
    </tr>   
    
</table>

<script>
$(function(){
	// Update Title
	$('#name').keyup(function(){
		
		var param = {
			method : 'update_name',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'group_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
});
</script>