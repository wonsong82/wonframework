<?php
//$elementList = array('Text','Link', 'Group', 'Image', 'Video');
$elementList = array('Text','Link','Group','Image');


$id = $params['id'];
$page = Won::get('Contents')->get_page($id);
$elements = Won::get('Contents')->get_elements($id);

?>
<table>
	<thead>
    	<td>Elements</td>
        <td>Contents of "<?=$page['uri'];?>"</td>
    <thead>
	<tr>    	
    	<td width="160" valign="top">
        	<ul class="element-list">
            	<?php foreach ($elementList as $item) { ?>
                <li class="ui-state-default">
                	<?=$item;?>
                </li>
                <?php } ?>
            </ul>
        </td>
        
        <td valign="top">
        	<ul class="page-element">
            	<?php foreach ($elements as $element) { ?>
                	
                    <li class="element-item ui-state-default <?=$element['type'];?>" type="<?=$element['type'];?>" elementid="<?=$element['id'];?>" style="position:relative">
                    	<?php if ($element['type']=='group') { ?>
                        	<div class="c"><?=$element['title'];?></div>
                        	<ul>
                            	<?php foreach ($element['elements'] as $child) { ?>
                                <!-- Childrens -->
                                <li class="element-item ui-state-default <?=$child['type'];?>" type="<?=$element['type'];?>" elementid="<?=$child['id'];?>" style="position:relative">
                                	<div class="c"><?=$child['title'];?></div>
                                    <a class="button edit" href="<?=Won::get('Config')->admin_url.'/'.Won::get('Config')->this_module.'/'.Won::get('Config')->this_module_page.'/edit=element&id='.$child['id'];?>&type=<?=$child['type'];?>">Edit</a>
                        <input elementid="<?=$child['id'];?>" class="xbutton" type="button" value="X" />
                                </li>
                                <!-- End Childrenns -->
                                <?php } ?>
                            </ul>
                        	<?php } else { ?>
                    		<div class="c"><?=$element['title'];?></div>
                        <?php } ?>
                        <a class="button edit" href="<?=Won::get('Config')->admin_url.'/'.Won::get('Config')->this_module.'/'.Won::get('Config')->this_module_page.'/edit=element&id='.$element['id'];?>&type=<?=$element['type'];?>">Edit</a>
                        <input elementid="<?=$element['id'];?>" class="xbutton" type="button" value="X" />
                    </li>
                    
                <?php } ?>
            </ul>
        </td>
    </tr>
    <tr><td colspan="2" class="load"></td></tr>
</table>

<style>
	.element-list li {
		width:100px;
		cursor:move;
		margin:0 5px 5px 5px; 	
		padding:5px;	
	}
	.page-element {
		display:block;
		width:auto;
		min-height:200px;
		background:#fff;
		margin-right:20px;
		margin-bottom:20px;
		padding:10px;
		
	}
	.page-element * {
		padding:4px;
		margin:4px;
	}
	.link, .text, .image, .video {
		height:17px;
		overflow:hidden;
	}
	
	.page-element .group ul {		
		min-height:60px;
				
	}
	
	
	.c {
		margin:0px;
		padding: 0px;
	}
	.edit {
		position:absolute;
		top:0px;
		right:32px;
	}
	.xbutton {
		position:absolute;
		top:0px;
		right:0px;
	}
	.element-item {
		cursor:move;
	}
</style>

<script>
$(function(){
		
	$('.element-list li').draggable({
		helper : 'clone'		
	});
	
	// first level
	$('.page-element').droppable({
		activeClass: "ui-state-default",
		hoverClass: "ui-state-hover",
		accept: '.element-list>li, .group ul>li',		
		drop: function(event, ui) {
			
			// if the ui is from element-list
			if (!ui.draggable.attr('type')) {
				var param = {
					method : 'add',
					type : ui.draggable.text().trim().toLowerCase(),
					page_id : <?=$id;?>
				}
				load_ajax('<?=Won::get('Config')->this_module?>', 'element_controller' , param, '.load', 'refresh');
			}
			
			// if the ui is from the group list
			else {
				var param = {
					method : 'update_parent',
					parent_id : 0,
					element_id : ui.draggable.attr('elementid')
				}
				load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, '.load', 'refresh');
			}
		}
	}).sortable({
		update : function (event, ui) {
			var items = [];
			$(event.target).find('>li').each(function(){
				items.push($(this).attr('elementid'));
			});
			items = items.join(',');
			var param = {
				method : 'update_sort',
				ids : items
			}
			load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, '.load');
		}
	});
	
	$('.group ul').droppable({
		activeClass: "ui-state-default",
		hoverClass: "ui-state-hover",
		accept: '.page-element>li, .group ul>li',
		greedy:true,
		drop: function(event, ui) {
			if (ui.draggable.attr('type') != 'group') { // cannot nest group into group
				var param = {
					method : 'update_parent',
					parent_id : $(event.target).parent().attr('elementid'),
					element_id : ui.draggable.attr('elementid')
				}
				load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, '.load', 'refresh');
			}
		}
	}).sortable({
		update : function(event, ui) {
			var items = [];
			$(event.target).find('>li').each(function(){
				items.push($(this).attr('elementid'));
			});
			items = items.join(',');
			var param = {
				method : 'update_sort',
				ids : items
			}
			load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, '.load');
		}
	});;
	
	
	
	
	// remove button
	$('.xbutton').click(function(){
		var e = $(this);
		var param = {
			method : 'remove',
			id : $(this).attr('elementid')
		}
		load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, '.load', function(data) {
			if (data=='') {
				e.parent().stop(true).animate({'opacity':0},400,function(){
					$(this).remove();
				});
			}
			else 
			alert(data);
		});
		return false;
	});
	
});

</script>