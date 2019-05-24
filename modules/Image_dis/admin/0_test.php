<?php
$image = new Image();
$imgs = $image->list_images('test',1);
$img = $imgs[0];


?>

<div class="canvas" style="width:<?=$img['img_thumb_w']?>px;height:<?=$img['img_thumb_h']?>px;">	
	<img class="img" src="<?=$img['path'].'/'.$img['img_original']?>"
    style="width:<?=$img['img_thumb_w']?>px;height:<?=$img['img_thumb_h']?>px;"   />
    <div class="frame" style="width:100px;height:100px;left:<?=$img['img_thumb_x']*-1?>px;top:<?=$img['img_thumb_y']*-1?>px;"></div>    
</div>


<script>
	$('.canvas').css('position', 'relative');
	$('.canvas .img').css({
		'position':'absolute',
		'display':'block'
	});
	$('.canvas .frame').css({
		'position':'absolute',
		'opacity':0.5,
		'background-color':'#ffffff'
	});
	
	$('.frame').draggable({containment:"parent"});
	
</script>


<form action="<?=THIS_MODULE_URL.'/ajax/controller.php';?>" method="post" enctype="multipart/form-data">
	<input type="file" name="won-image-uploaded-file" />
    <button type="submit">OK</button>
    <input type="hidden" name="url" value="<?=$page->url?>"/>
    <input type="hidden" name="method" value="add"/>
</form>

