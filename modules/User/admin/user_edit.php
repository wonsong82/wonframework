<?php
$id = $params['edit'];
Won::set(new User());
$user = Won::get('User')->get_user_by_id($id);
$groups = Won::get('User')->get_groups();

?>
<table>
	<tr>
    	<td><h1>User <?=$user['username'];?></h1></td>
        <td></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Name</h3></td></tr>
    <tr><td><input type="text" id="name" value="<?=$user['name'];?>" /></td><td width="16" class="load"></td></tr>
    <tr><td colspan="2" class="error"></td></tr>
        
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Email</h3></td></tr>
    <tr><td><input type="text" id="email" value="<?=$user['email'];?>" /></td><td width="16" class="load"></td></tr>
    <tr><td class="error"></td>   
    <tr><td colspan="2" class="error"></td></tr>
    
    <tr><td>&nbsp;</td></tr>
    
    
    <tr>
    	<td colspan="2"><h3>Password</h3></td>
    </tr>
    <tr><td><a class="button" href="<?=Won::get('Config')->admin_url . '/' . Won::get('Config')->this_module . '/' . Won::get('Config')->this_module_page.'/password='.$id?>">Change Password</a></td><td width="16" class="load"></td></tr>
   
       
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Active</h3></td>
    <tr><td align="left"><input style="width:auto" type="checkbox" id="active"<?=$user['active']? 'checked="checked"' : '';?> /></td><td width="16" class="load"></td></tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr><td colspan="2"><h3>Banned</h3></td>
    <tr><td align="left"><input style="width:auto" type="checkbox" id="banned"<?=$user['banned']? 'checked="checked"' : '';?> /></td><td width="16" class="load"></td></tr>    
    
    <tr><td>&nbsp;</td></tr>
            
    <tr><td>Last Login :<?=$user['last_login'];?></td></tr>
    <tr><td>Joined On  :<?=$user['created_time'];?></td></tr>
</table>

<table>

	<tr>
    	<td width="50%"><h1><?=$user['username'];?>'s Groups</h1></td>
        <td width="50%" class="load"></td>
    </tr>
    
    <?php for ($i=0; $i<count($groups); $i++) { ?>
    	<?php if ($i%2 == 0) { echo '<tr>'; }?>
    		
            <td width="50%"><input groupname="<?=$groups[$i]['name'];?>" class="group-name" style="width:auto" type="checkbox"<?=in_array($groups[$i]['name'], $user['groups'])? 'checked="checked"' : '';?> /> <?=$groups[$i]['name'];?></td>
    
    	<?php if ($i%2 == 1) { echo '</tr>'; }?>
    <?php } ?>
    <tr><td>&nbsp;</td></tr>
</table>

<script>
$(function(){
	$('#name').keyup(function(){
		
		var param = {
			method : 'update_name',			
			id : <?=$id?>,
			val : $(this).val()
		};		
		var error = $(this).parent().parent().next().find('.error');
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target, error); 		
		return false;
	});
	
	$('#email').keyup(function(){
		
		var param = {
			method : 'update_email',			
			id : <?=$id?>,
			val : $(this).val()
		};		
		var error = $(this).parent().parent().next().find('.error');
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target, error); 		
		return false;
	});
	
	
	$('#active').click(function(){
		var value = ($(this).attr('checked') == 'checked')? 1 : 0;
		
		var param = {
			method : 'update_active',			
			id : <?=$id?>,
			val : value
		};		
		var loading_target = $(this).parent().parent().find('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target);			
	});
	
	$('#banned').click(function(){
		var value = ($(this).attr('checked') == 'checked')? 1 : 0;
		
		var param = {
			method : 'update_banned',			
			id : <?=$id?>,
			val : value
		};		
		var loading_target = $(this).parent().parent().find('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target);			
	});
	
	$('.group-name').click(function(){
		var param = {
			gname : $(this).attr('groupname'),
			uname : '<?=$user['username'];?>',
			method : $(this).attr('checked') == 'checked' ? 'add_to_group' : 'remove_from_group'
		};
		var loading_target = $(this).parent().parent().parent().find('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target,'#msg');
	});
	
	
	
});
</script>





