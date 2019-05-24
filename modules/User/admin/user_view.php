<?php
Won::set(new User());
$users = Won::get('User')->get_users();
?>
<table>
	<thead>
    	<td>Username</td>
        <td>Name</td>
        <td width="50">&nbsp;</td>
        <td width="50">&nbsp;</td>
        <td width="16">&nbsp;</td>       
    </thead>
    
    <?php foreach ($users as $user) { ?>
    <tr>
    	<td><h2><?=$user['username'];?></h2></td>
        <td><?=$user['name'];?></td>
        <td><a class="button" href="<?=Won::get('Config')->admin_url .'/'. Won::get('Config')->this_module .'/' . Won::get('Config')->this_module_page . '/edit=' .$user['id']?>">Edit</a></td>
        <td><button rowid="<?=$user['id'];?>" class="remove">Delete</button></td>
        <td class="load"></td>
    </tr>
    <?php } ?>
    
</table>

<style>
tr td {padding:5px}
tr+tr td {border-top:1px dotted #dddddd}
</style>

<script>
$(function(){
	$('.remove').click(function(){
		var param = {
			method : 'remove',
			id : $(this).attr('rowid')
		}
		var loading_target = $(this).parent().parent().find('.load');	
		var therow = $(this);
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'user_controller', param, loading_target, function(data){			
			therow.parent().parent().hide(1000);		
		});
		return false;
	});	
});
</script>