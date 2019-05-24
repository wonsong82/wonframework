<?php
$nav = Won::get('Product')->categories();
$cat_name = Won::get('Permalink')->params['params'] ? Won::get('Permalink')->params['params'] : null;

// get item
$items = array();
if ($cat_name)
	$items = Won::get('Product')->items_by_title($cat_name);



?>
<table>
	<tr>
    	<td width="160" valign="top">
        	<ul class="nav">
            	<?php foreach ($nav as $menu) { ?>
                <li<?=$cat_name==$menu['url_friendly_title']? ' class="selected"' : '';?>>
                	<a href="<?=Won::get('Config')->admin_url.'/'.Won::get('Config')->this_module.'/'.Won::get('Config')->this_module_page.'/'.$menu['url_friendly_title'];?>">
						<?=$menu['title']?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </td>
        <td valign="top">        	
        	<ul class="sort">
            	<?php foreach ($items as $item) { ?>
                <li class="ui-state-default" rowid="<?=$item['id']?>">
                	<table>
                    	<tr>
                        	<td><?=$item['title']?></td>
                            <td width="20" class="load"></td>
                            <td width="50"><a class="detail-button button" href="<?=Won::get('Config')->admin_url.'/'. Won::get('Config')->this_module .'/' . Won::get('Config')->this_module_page . '/edit=' .$item['id']?>">Detail</a></td>
                            <td width="50"><button class="remove-button" rowid="<?=$item['id']?>">Remove</button></td>
                        </tr>
                    </table>
                </li>
                <?php } ?>
            </ul>
            
        </td>
    </tr>
</table>

<?php if ($cat_name) { ?>
<table id="add-new">	
	<tr>
    	<td width="150"><h3>Add Item (Item Title) : </h3></td>
    	<td><input type="text" id="new-title" autofocus="autofocus" /></td>
        <td class="load" width="20"></td>    	
		<td width="40"><button id="add-btn" type="submit">Add</button></td>
    </tr>       
</table>
<?php } ?>



<script>
$(function(){
	
	// ADD Item
	$('#add-new #add-btn').click(function(){
		
		var param = {
			method : 'add_item_by_title',
			cat : '<?=$cat_name?>',	
			title : $('#new-title').val()			
		};		
		var loading_target = $(this).parent().find('.load');	
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	$('#add-new #new-title').keypress(function(e) {
		if (e.keyCode==13)
		{
			var param = {
				method : 'add_item_by_title',
				cat : '<?=$cat_name?>',	
				title : $('#new-title').val()			
			};		
			var loading_target = $(this).parent().find('.load');	
			load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, 'refresh');
			return false; 		
		}		
	});
	
	
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
			load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg');	
		}
	});
	$('.sort').disableSelection();
	
	
	
	
	
	// Remove Cateogry
	$('.remove-button').click(function(){
		
		var param = {
			method : 'remove',			
			id : $(this).attr('rowid')		
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, 'refresh'); 
		
		return false;
	});
	
	
});
</script>

