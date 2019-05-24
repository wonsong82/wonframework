
<table id="group-category">
	<tr>
    	<td>
        	<ul class="sort">
            	<?php foreach (Won::get('User')->get_groups() as $group) { 
							$editable = $group['editable']? true : false;?>
                <li class="ui-state-default" rowid="<?=$group['id']?>">
                	<table>
                    	<tr>
                        	<td><?=$group['name'];?></td>
                            <td width="20" class="load"></td>
                            <td width="50"><?php if ($editable) {?><a class="edit-button button" href="<?=Won::get('Config')->admin_url .'/'. Won::get('Config')->this_module .'/' . Won::get('Config')->this_module_page . '/edit=' .$group['id']?>">Edit</a><?php } ?></td>
                            <td width="50"><?php if ($editable) {?><button class="remove-button" rowid="<?=$group['id']?>">Remove</button><?php } ?></td>
                        </tr>
                    </table>
                </li>
                <?php } ?>
            </ul>
            <div class="load"></div>
        </td>
    </tr>
</table>

<table id="add-new">
	<tr>
    	<td width="90">Group Name</td>
        <td width="400"><input type="text" id="new-group-name" autofocus /></td>
        <td></td>
        <td class="load"></td>
        <td width="40"><button id="add-btn" type="submit">Add</button></td>
    </tr>
</table>

<script>
$(function(){
	
	// Sort Group
	$('.sort').sortable({
		update : function(event, ui) {
			var items = [];
			$(event.target).find('li').each(function(){
				items.push($(this).attr('rowid'));
			});
			
			items = items.join(',');
			var param = {
				method : 'update_sort',
				ids : items
			}
			
			var loading_target = $(event.target).parent().find('.load');			
			load_ajax('<?=Won::get('Config')->this_module?>', 'group_controller', param, loading_target, '#msg');	
		}
	});
	$('.sort').disableSelection();
	
	
	// ADD Group
	$('#add-new #add-btn').click(function(){
		
		var param = {
			method : 'add',			
			name : $('#new-group-name').val()			
		};		
		var loading_target = $(this).parent().parent().find('.load');			
		load_ajax('<?=Won::get('Config')->this_module?>', 'group_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	// ADD Group
	$('#add-new #new-group-name').keypress(function(e){
		if (e.keyCode == 13)
		{		
			var param = {
				method : 'add',			
				name : $('#new-group-name').val()			
			};					
			var loading_target = $(this).parent().parent().find('.load');	
			load_ajax('<?=Won::get('Config')->this_module?>', 'group_controller', param, loading_target, 'refresh'); 
			
			return false;
		}
	});
	
	
	// Remove Cateogry
	$('.remove-button').click(function(){
		
		var param = {
			method : 'remove',			
			id : $(this).attr('rowid')		
		};		
		var loading_target = $(this).parent().parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'group_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	
});
</script>