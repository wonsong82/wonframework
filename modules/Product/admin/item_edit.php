<?php
Won::set(new Images());
Won::set(new File());
Won::set(new Videos());

$id = $params['edit'];
$item = Won::get('Product')->item($id);
$uploadDir = Won::get('Config')->content_dir.'/uploads';


$images = Won::get('Images')->getImages($item['images']);
$videos = Won::get('Videos')->getVideos($item['videos']);
$videoDir = Won::get('Config')->content_dir.'/'.Won::get('Videos')->contentFolder;


?>

<table id="item-edit">
	<tr>
    	<td colspan="2"><h3>Item Title</h3></td>        
    </tr>
    <tr>
    	<td><input id="title" value="<?=htmlspecialchars($item['title'])?>"/></td>
        <td width="16" class="load"></td>
    </tr> 
    
    <tr>
    	<td colspan="2"><h3>Url-Friendly-Title</h3></td>        
    </tr>
    <tr>
    	<td colspan="2"><h2 id="url-friendly-title"><?=$item['url_friendly_title']?></h2></td>
    </tr>
    
     <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2"><h3>Subtitle</h3></td>        
    </tr>
    <tr>
    	<td><input id="subtitle" value="<?=htmlspecialchars($item['subtitle'])?>"/></td>
        <td width="16" class="load"></td>
    </tr>   
    
    
    <tr><td>&nbsp;</td></tr>    
    <tr>
    	<td colspan="2"><b>Description</b></td>
    </tr>
    <tr>
    	<td><textarea id="description" rows="5"><?=htmlspecialchars($item['description'])?></textarea></td>
        <td width="16" class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2"><h3>Item ID (Unique ID Number)</h3></td>        
    </tr>
    <tr>
    	<td><input id="item_id" style="width:200px" value="<?=$item['item_id']?>"/></td>
        <td width="16" class="load"></td>
    </tr>
    
     <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2"><h3>Price</h3></td>        
    </tr>
    <tr>
    	<td><input id="price" style="width:200px" value="<?=$item['price']?>"/></td>
        <td width="16" class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2"><h3>Weight</h3></td>        
    </tr>
    <tr>
    	<td><input id="weight" style="width:200px" value="<?=$item['weight']?>"/></td>
        <td width="16" class="load"></td>
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
    	<td colspan="2"><h3>Videos</h3></td>
    </tr>
    <tr>
    	<td>
        	<div id="video-container">
            	<ul class="video-sortable">
                <?php foreach ($videos as $video) { ?>
                	<li videoid="<?=$video['id']?>">
                    	<img src="<?=$video['thumb']?>"/>
                        <div class="control" style="text-align:right;padding-bottom:5px;position:absolute;top:7px;right:7px;display:none;">
                        	<button videoid="<?=$video['id']?>" class="delbtn">Del</button>
                        </div>
                    </li>
                <?php } ?>
                </ul>
            </div>
            <div style="clear:both">
            <button id="add-video-btn">Add Video</button>
            </div>
        </td>
        <td width="16" class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2"><h3>Available</h3></td>              
    </tr>
    <tr>
    	<td align="left"><input id="available" style="width:auto" type="checkbox"<?=$item['available']? ' checked="checked"' : '';?>/></td>
        <td width="16" class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2"><h3>Stock Counts (-1 for infinite)</h3></td>              
    </tr>
    <tr>
    	<td><input style="width:50px" id="stock" value="<?=$item['stock']?>"/></td>
        <td width="16" class="load"></td>
    </tr>   
    
    
    <tr><td>&nbsp;</td></tr>
     <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2">Created on <?=date('M jS, Y l \a\t g:m a', strtotime($item['created_time']))?></td>        
    </tr>
    <tr>
    	<td colspan="2">Last Modified on <?=date('M jS, Y l \a\t g:m a', strtotime($item['modified_time']))?></td>        
    </tr>
    
    
    
    
    
    
    <!-- IMAGE -->
    
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
	$('#item-edit #title').keyup(function(){
		
		var param = {
			method : 'update_title',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#url-friendly-title'); 
		
		return false;
	});
	
	// Update Subtitle
	$('#item-edit #subtitle').keyup(function(){
		
		var param = {
			method : 'update_subtitle',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Description
	$('#item-edit #description').keyup(function(){
		
		var param = {
			method : 'update_description',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Item ID
	$('#item-edit #item_id').keyup(function(){
		
		var param = {
			method : 'update_itemid',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Price
	$('#item-edit #price').keyup(function(){
		
		var param = {
			method : 'update_price',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Weight
	$('#item-edit #weight').keyup(function(){
		
		var param = {
			method : 'update_weight',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
	
	// Update Avaiable
	$('#item-edit #available').click(function(){
		var value = ($(this).attr('checked') == 'checked')? 1 : 0;
		
		var param = {
			method : 'update_available',			
			id : <?=$id?>,
			value : value
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg');	
		
	});
	
	// Update Stock Counts
	$('#item-edit #stock').keyup(function(){
		
		var param = {
			method : 'update_stock',			
			id : <?=$id?>,
			value : $(this).val()
		};		
		var loading_target = $(this).parent().find('.load');	
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, loading_target, '#msg'); 
		
		return false;
	});
});


/* Images */
function imgUploaded(data, target) {
	// ajax to image creator
	var param = {
		method : 'add_image',
		item_id : <?=$id;?>,
		image : '<?=$uploadDir;?>'+ '/' + data,
		width : 258,
		twidth : 100,
		theight : 100
	}
	var loading = target.closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading, function(status) {
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
			load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading,'#msg');
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
		load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading, 'refresh');		
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
	load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading, 'refresh');	
}
<?php foreach ($images as $img) { ?>
$(function() {
	$('#image-container li .editbtn[imageid="<?=$img['id']?>"]').click(function(){
		<?=Won::get('Images')->imageEditor($img['id'], 'imgEdited');?>
	});
});
<?php } ?>


/* video */
$(function(){
	$('.video-sortable').sortable({
		update:function(event, ui){
			var items = [];
			$(event.target).find('>li').each(function(){
				items.push($(this).attr('videoid'));
			});
			items = items.join(',');
			var param = {
				method : 'update_video_sort',
				item_id : <?=$id?>,
				ids : items
			};
			var loading = $(event.target).closest('tr').find('.load');
			load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading,'#msg');
		}
	});
	
	$('#video-container li').hover(
		function(){
			$(this).find('.control').css('display','block');			
		}, function(){
			$(this).find('.control').css('display','none');				
	});
	
	$('#video-container li .delbtn').click(function(){
		var param = {
			'method' : 'remove_video',
			'item_id' : <?=$id;?>,
			'video_id' : $(this).attr('videoid')
		};
		var loading = $(this).closest('tr').find('.load');
		load_ajax('<?=Won::get('Config')->this_module;?>', 'item_controller', param, loading, 'refresh');		
	});
});


function videoUploaded(data, target) {
	target.parent().find('.info').html(data);
	target.closest('.video-adder').find('.shade').remove();
	
}

function videoThumbUploaded(data, target) {
	target.parent().find('.info').html(data);
}

$(function(){
	$('#add-video-btn').click(function(){
			var video_adder = $('<div class="video-adder"></div>');
			var video_bg=$('<div></div>');
			video_bg.css({'width':'100%','height':'100%','background':'#000','position':'fixed','top':'0px','left':'0px','opacity':0.5}).appendTo(video_adder);
			var video_ebg=$('<div></div>');
			video_ebg.css({'width':'360px', 'background':'#fff','position':'fixed','top':'50%','left':'50%','overflow':'auto','margin-left':'-200px','margin-top':'-100px','padding':'20px'}).appendTo(video_adder);
			
			var video_xbtn = $('<div>X</div>');
			video_xbtn.css({'width':'15px','height':'15px','color':'#fff','font-family':'arial','font-size':'15px','cursor':'pointer','margin':'0px','padding':'0px','position':'fixed','left':'50%','top':'50%','margin-top':'-120px','margin-left':'190px'}).appendTo(video_adder);			
			video_xbtn.click(function(){
				video_adder.remove();
			});
			
									
			var video_file_uploader=$('<div style="position:relative"></div>');
			video_file_uploader.append('<h1>Video Title</h1>');
			var video_file_title=$('<input type="text"/>');
			video_file_title.appendTo(video_file_uploader);
			video_file_uploader.append('<br/><br/>');
			video_file_uploader.append('<h1>Video (mp4 format only)</h1>');
			var video_file_info=$('<div class="info"></div>');
			video_file_info.appendTo(video_file_uploader);
			var video_file_upload_btn=$('<button>Video File</button>');
			video_file_upload_btn.appendTo(video_file_uploader);
			video_file_uploader.appendTo(video_ebg);
			/*var video_shade=$('<div></div>');
			video_shade.css({'opacity':.1, 'background':'#ffffff','width':'100%','height':'100%','position':'absolute','top':'-10px','left':'-10px','padding':'10px'});
			video_file_uploader.append(video_shade);
			*/
			video_ebg.append('<br/><br/>');
			
			var thumb_file_uploader=$('<div style="position:relative"></div>');
			thumb_file_uploader.append('<h1>Thumbnail Image</h1>');
			var thumb_file_info=$('<div class="info"></div>');
			thumb_file_info.appendTo(thumb_file_uploader);
			var thumb_file_upload_btn=$('<button>Thumb File</button>');
			thumb_file_upload_btn.appendTo(thumb_file_uploader);
			thumb_file_uploader.appendTo(video_ebg);
			var thumb_shade=$('<div class="shade"></div>');
			thumb_shade.css({'opacity':.1, 'background':'#ffffff','width':'100%','height':'100%','position':'absolute','top':'-10px','left':'-10px','padding':'10px'});
			thumb_file_uploader.append(thumb_shade);
			
			video_ebg.append('<br/>');
			var video_msg=$('<div style="color:#f00"></div>');			
			var video_ok_btn=$('<button>OK</button>');
			video_msg.appendTo(video_ebg);
			video_ok_btn.appendTo(video_ebg);
			
			video_file_upload_btn.click(function(){
				<?=Won::get('File')->uploader($videoDir, 'video/mp4', 'videoUploaded');?>
			});
			
			thumb_file_upload_btn.click(function(){
				<?=Won::get('File')->uploader($videoDir, 'image/*', 'videoThumbUploaded');?>
			});
			
			video_ok_btn.click(function(){
				video_msg.html('');
				if (video_file_title.val().trim()=='') {
					video_msg.html('Title is missing').show(500).delay(2000).hide(500);
					return false;
				}
				
				if (video_file_info.html()=='') {
					video_msg.html('Please upload a video').show(500).delay(2000).hide(500);
					return false;
				}
				if (thumb_file_info.html()=='') {
					video_msg.html('Please upload a thumbnail image').show(500).delay(2000).hide(500);
					return false;
				}
				// if good 
				var param={
					'method':'add_video',
					'item_id':<?=$id;?>,
					'title':video_file_title.val().trim(),
					'video':video_file_info.html(),
					'thumb':thumb_file_info.html()
				}
				load_ajax('<?=Won::get('Config')->this_module?>', 'item_controller', param, null, 'refresh');
				video_adder.remove();
			});
		
			$('body').append(video_adder);
			
			
	});
});


</script>