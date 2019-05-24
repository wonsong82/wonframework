<table id="product-category">
	<tr>
    	<td>
        	<ul class="sort">            	
            	<?php foreach (Won::get('Product')->categories() as $cat) { ?>
            	<li class="ui-state-default" rowid="<?=$cat['id']?>">
					<table>
                    	
                        <tr>
                        	<td><?=$cat['title']?></td>
                        	<td width="20" class="load"></td>
                        	<td width="50"><a class="detail-button button" href="<?=Won::get('Config')->admin_url .'/'. Won::get('Config')->this_module .'/' . Won::get('Config')->this_module_page . '/edit=' .$cat['id']?>">Detail</a></td>
                            <td width="50"><button class="remove-button" rowid="<?=$cat['id']?>">Remove</button></td>
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
    	<td width="40">Title</td>
    	<td width="400"><input type="text" id="new-title" autofocus="autofocus" /></td>
        <td></td>
        <td class="load"></td>    	
		<td width="40"><button id="add-btn" type="submit">Add</button></td>
    </tr> 
      
</table>

<script>
$(function(){
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
			load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, '#msg');	
		}
	});
	$('.sort').disableSelection();
	
	
	// ADD Category
	$('#add-new #add-btn').click(function(){
		
		var param = {
			method : 'add',			
			title : $('#new-title').val()			
		};		
		var loading_target = $(this).parent().parent().find('.load');	
		load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	// ADD Category
	$('#add-new #new-title').keypress(function(e){
		if (e.keyCode == 13)
		{		
			var param = {
				method : 'add',			
				title : $('#new-title').val()			
			};		
			var loading_target = $(this).parent().parent().find('.load');	
			load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, 'refresh'); 
			
			return false;
		}
	});
	
	
	// Remove Cateogry
	$('#product-category .remove-button').click(function(){
		
		var param = {
			method : 'remove',			
			id : $(this).attr('rowid')		
		};		
		var loading_target = $(this).parent().parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	
});
</script>
