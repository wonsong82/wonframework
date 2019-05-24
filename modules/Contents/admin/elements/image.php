<?php
Won::set(new Images());

$uploadDir = Won::get('Config')->content_dir . '/uploads';
$imgDir = Won::get('Config')->content_dir . '/' . Won::get('Images')->contentFolder;
$imgUrl = Won::get('Config')->content_url . '/' . Won::get('Images')->contentFolder;

$img = null;
if ($element['value']) {
	$img = Won::get('Images')->getImage($element['value']);
	$src = ' src="' . $img['img'] . '"';
	
	$imgID = ' imageid="' . $img['id'] . '"';
	$thSrc = ' src="' . $img['thumb'] . '"';
} else {
	$src = '';
	$thSrc = '';
	$imgID = '';
}


Won::set(new File());
?>
<table>
	<tr>
    	<td class="load"></td>
    </tr> 
	<tr>
    	<td style="position:relative">
        	        	
        	<img<?=$imgID;?> id="img"<?=$src;?>/>
            <div>            	
            	<button id="change">Change / Add a new image</button>
            	<?php if ($img!=null) {?><button class="image-edit" imageid="<?=$img['id'];?>">Edit this image</button>
                <!--<button class="image-thumb">Edit thumbnail image</button>--><?php }?>
            </div>
            <!--<div class="thumb"><img  style="padding:4px; background:#fff" id="thumbImg"<?=$thSrc;?> /></div>-->
        </td>
    </tr>
       
</table>

<script>
$('#change').click(function(){
	<?=Won::get('File')->uploader($uploadDir, 'image/jpeg', 'imageUploaded');?> 
});

function imageUploaded(data, target) {
	
	// ajax to image creator
	var param = {
		method : 'add',
		element_id : <?=$id;?>,
		image : '<?=$uploadDir;?>'+ '/' + data,
		width : <?=$settings['imgSize']['width'];?>,
		height : <?=$settings['imgSize']['height'];?>,
		twidth : <?=$settings['thumbSize']['width'];?>,
		theight : <?=$settings['thumbSize']['height'];?>
	}
	var loading = target.closest('table').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'image_controller', param, loading, function(status) {
		if (status=='') {
			window.location.href = window.location.href;
		} 
		else {
			$('#msg').html(status);
		}
	});
}


<?php if ($img!=null) { ?>
function updateImage(data, target) {
	// ajax to image updator
	var param = {
		method:'update_image',
		element_id : <?=$id;?>,
		image_id:data.id,
		width:data.width,
		height:data.height,
		x:data.x,
		y:data.y,
		x2:data.x2,
		y2:data.y2
	};
	var loading = target.closest('table').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'image_controller', param, loading, 'refresh');	
}
$('.image-edit').click(function(){
	<?=Won::get('Images')->imageEditor($img['id'], 'updateImage'); ?>
});



function updateThumb(data, target) {
	var param = {
		method:'update_thumb',
		element_id : <?=$id;?>,
		image_id: data.id,
		width: data.width,
		height: data.height,
		x: data.x,
		y: data.y,
		x2: data.x2,
		y2: data.y2
	}
	var loading = target.closest('table').find('.load');
	load_ajax('<?=Won::get('Config')->this_module;?>', 'image_controller', param, loading, 'refresh');
}
$('.image-thumb').click(function(){
	<?=Won::get('Images')->thumbEditor($img['id'],  'updateThumb'); ?>
});



<?php } ?>
</script>

