<?php
Won::set(new File());
Won::set(new Images());

$id = $params['edit'];
$cat = Won::get('Product')->category($id);
$uploadDir = Won::get('Config')->content_dir . '/uploads';

$images = Won::get('Images')->getImages($cat['images']);
?>

<table id="cat-edit">
	<tr>
    	<td colspan="2"><b>Title</b></td>        
    </tr>
    <tr>
    	<td><input id="title" value="<?=htmlspecialchars($cat['title'])?>"/></td>
        <td width="16" class="load"></td>
    </tr> 
    
    <tr>
    	<td colspan="2"><b>Url-Friendly-Title</b></td>        
    </tr>
    <tr>
    	<td colspan="2"><h2 id="url-friendly-title"><?=$cat['url_friendly_title']?></h2></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2"><h3>Images</h3></td>
    </tr>
    
    <tr>
    	<td>
        	<div id="image-container">
            	<ul class="image-sortable">
            	<?php foreach ($images as $image) { ?>
                	<li imageid="<?=$image['id']?>">
                    	<img src="<?=$image['thumb']?>"/>
                        <div class="control" style="text-align:right;padding-bottom:5px;position:absolute;top:7px;right:7px;display:none;">
                        	<button class="editbtn" imageid="<?=$image['id']?>">Edit</button><button imageid="<?=$image['id']?>" class="delbtn">Del</button>
                        </div>
                    	
                    </li>
                <?php } ?>
				</ul> 
            </div>
            <div style="clear:both">
            <button id="add-image-btn">Add Image</button>
            </div>
        </td>
         <td width="16" class="load"></td>
    </tr>
    
        
    <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2"><b>Description</b></td>
    </tr>
    <tr>
    	<td><textarea id="description" rows="5"><?=htmlspecialchars($cat['description'])?></textarea></td>
        <td width="16" class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <!-- IMAGE -->
    
    <tr>
    	<td colspan="2">
        	<h3>Available</h3>
        </td>        
    </tr>
    <tr>
    	<td align="left">
        	<input<?=$cat['available']? ' checked="checked"' : '';?> style="width:auto" type="checkbox" id="available" />
        </td>
        <td width="16" class="load"></td>
    </tr>
    
</table>

<style>
#image-container li {
	float:left; width:100px;  background:#cccccc;padding:5px;margin:3px; cursor:move; position:relative;
}
#video-container li {
	float:left; width:100px;  background:#cccccc;padding:5px;margin:3px; cursor:move; position:relative;
}
#video-container li img {
	width:100px;
}
</style>

<script>
$(function(){
	// Update Title
	$('#cat-edit #title').keyup(function(){
		
		var param = {
			method : 'update_title',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, '#url-friendly-title'); 
		
		return false;
	});
	
	// Update Description
	$('#cat-edit #description').keyup(function(){
		
		var param = {
			method : 'update_description',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Avaialblity
	$('#available').click(function(){
		var value = ($(this).attr('checked') == 'checked')? 1 : 0;
		
		var param = {
			method : 'update_available',			
			id : <?=$id?>,
			value : value
		};		
		var loading_target = $(this).closest('tr').find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'category_controller', param, loading_target, '#msg');	
		
	});
});


/* Images */
function imgUploaded(data, target) {
	// ajax to image creator
	var param = {
		method : 'add_image',
		item_id : <?=$id;?>,
		image : '<?=$uploadDir;?>'+ '/' + data,
		width : 227,
		twidth : 100,
		theight : 100
	}
	var loading = target.closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading, function(status) {
		if (status=='') {
			window.location.href = window.location.href;
		} 
		else {
			$('#msg').html(status);
		}
	});
}
$(function(){
	$('#add-image-btn').click(function(){
		<?=Won::get('File')->uploader($uploadDir, 'image/jpeg', 'imgUploaded');?>
	});
});

function imgEdited(data, target) {
	var param = {
		method:'update_image',
		image_id:data.id,
		width:data.width,
		height:data.height,
		x:data.x,
		y:data.y,
		x2:data.x2,
		y2:data.y2
	};
	var loading = target.closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading, 'refresh');	
}
<?php foreach ($images as $img) { ?>
$(function() {
	$('#image-container li .editbtn[imageid="<?=$img['id']?>"]').click(function(){
		<?=Won::get('Images')->imageEditor($img['id'], 'imgEdited');?>
	});
});
<?php } ?>

$(function(){
	$('.image-sortable').sortable({
		update:function(event, ui){
			var items = [];
			$(event.target).find('>li').each(function(){
				items.push($(this).attr('imageid'));
			});
			items = items.join(',');
			var param = {
				method : 'update_image_sort',
				item_id : <?=$id?>,
				ids : items
			};
			var loading = $(event.target).closest('tr').find('.load');
			load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading,'#msg');
		}
	});
	
	$('#image-container li').hover(
		function(){
			$(this).find('.control').css('display','block');			
		}, function(){
			$(this).find('.control').css('display','none');				
	});
	
	$('#image-container li .delbtn').click(function(){
		var param = {
			'method' : 'remove_image',
			'item_id' : <?=$id;?>,
			'image_id' : $(this).attr('imageid')
		};
		var loading = $(this).closest('tr').find('.load');
		load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading, 'refresh');		
	});	
});


/*



function imageUploaded(data, target) {
	// ajax to image creator
	var param = {
		method : 'image',
		cat_id : <?=$cat['id'];?>,
		image : '<?=$uploadDir;?>'+ '/' + data,
		width : 227,
		height : 131,
		twidth : 50,
		theight : 50
	}
	var loading = target.closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading, function(status) {
		if (status=='') {
			window.location.href = window.location.href;
		} 
		else {
			$('#msg').html(status);
		}
	});
}

$(function(){
	// Add
	$('#add-image-btn').click(function(){
		<?=Won::get('File')->uploader($uploadDir, 'image/jpeg', 'imageUploaded');?> 
	});
});

function imageEdited(data, target) {
	var param = {
		method:'update_image',
		image_id:data.id,
		width:data.width,
		height:data.height,
		x:data.x,
		y:data.y,
		x2:data.x2,
		y2:data.y2
	};
	var loading = target.closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'category_controller', param, loading, 'refresh');
}
<?php if ($img) { ?>
$(function(){
	// Edit
	$('#edit-image').click(function(){
		<?=Won::get('Images')->imageEditor($img['id'], 'imageEdited'); ?>
	});
});
<?php } ?>
*/

</script>