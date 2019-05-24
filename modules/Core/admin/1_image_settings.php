<?php
Won::set(new Images());
$imgSize = Won::get('Images')->imgSize;
$contentFolder = Won::get('Images')->contentFolder;
$thumbSize = Won::get('Images')->thumbSize;
?>

<table>
	<tr>
    	<td colspan="4"><h3>Default Image Size</h3></td>
        <td width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Width (px)</td>
        <td width="50"><input id="imgw" type="text" value="<?=$imgSize['width']? $imgSize['width'] : '';?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td width="60">Height (px)</td>
        <td width="50"><input id="imgh" type="text" value="<?=$imgSize['height']!=''? $imgSize['height'] :'';?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td colspan="4">The uploaded image will be resized, keeping its proportion, either cropped or left black areas.</td>
    </tr>
    <tr>
    	<td colspan="4" class="error"></td>
    </tr>
</table>

<table>
	<tr>
    	<td colspan="4"><h3>Default Thumbnail Image Size</h3></td>
        <td width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Width (px)</td>
        <td width="50"><input id="thw" type="text" value="<?=$thumbSize['width'];?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td width="60">Height (px)</td>
        <td width="50"><input id="thh" type="text" value="<?=$thumbSize['height'];?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td colspan="4" class="error"></td>
    </tr>
</table>



<script>
$(function(){
	$('#imgw').keyup(function() {
		var param = {
			'method' : 'update_img_size',
			'imgw' : $('#imgw').val(),
			'imgh' : $('#imgh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'image_setting_controller', param, loading, msg);		
	});
	
	$('#imgh').keyup(function() {
		var param = {
			'method' : 'update_img_size',
			'imgw' : $('#imgw').val(),
			'imgh' : $('#imgh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'image_setting_controller', param, loading, msg);
	});
	
	$('#thw').keyup(function() {
		var param = {
			'method' : 'update_thumb_size',
			'thw' : $('#thw').val(),
			'thh' : $('#thh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'image_setting_controller', param, loading, msg);		
	});
	
	$('#thh').keyup(function() {
		var param = {
			'method' : 'update_thumb_size',
			'thw' : $('#thw').val(),
			'thh' : $('#thh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'image_setting_controller', param, loading, msg);
	});
	
	
});
</script>