<?php
Won::set(new Settings());
$settings = Won::get('Settings')->getSettings('Contents');

$imageSizeWidth = isset($settings['imgSize']['width'])? $settings['imgSize']['width'] : '';
$imageSizeHeight = isset($settings['imgSize']['width'])? $settings['imgSize']['height'] : ''; 
$thumbSizeWidth = isset($settings['thumbSize']['width'])? $settings['thumbSize']['width'] : '';
$thumbSizeHeight = isset($settings['thumbSize']['width'])? $settings['thumbSize']['height'] : ''; 

?>

<table>
	<tr>
    	<td colspan="4"><h3>Default Image Size</h3></td>
        <td width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Width (px)</td>
        <td width="50"><input id="imgw" type="text" value="<?=$imageSizeWidth;?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td width="60">Height (px)</td>
        <td width="50"><input id="imgh" type="text" value="<?=$imageSizeHeight;?>"/></td>
        <td class="load"></td>       
    </tr>
    
    <tr>
    	<td colspan="4" class="error"></td>
    </tr>
    <tr>
    	<td colspan="4">If both fields are missing, values will be defaulted from the general setting.</td>
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
        <td width="50"><input id="thw" type="text" value="<?=$thumbSizeWidth;?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td width="60">Height (px)</td>
        <td width="50"><input id="thh" type="text" value="<?=$thumbSizeHeight;?>"/></td>
        <td class="load"></td>       
    </tr>
    <tr>
    	<td colspan="4" class="error"></td>
    </tr>
    <tr>
    	<td colspan="4">If both fields are missing, values will be defaulted from the general setting.</td>
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
		load_ajax('<?=Won::get('Config')->this_module?>', 'settings_controller', param, loading, msg);		
	});
	
	$('#imgh').keyup(function() {
		var param = {
			'method' : 'update_img_size',
			'imgw' : $('#imgw').val(),
			'imgh' : $('#imgh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'settings_controller', param, loading, msg);
	});
	
	$('#thw').keyup(function() {
		var param = {
			'method' : 'update_thumb_size',
			'thw' : $('#thw').val(),
			'thh' : $('#thh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'settings_controller', param, loading, msg);		
	});
	
	$('#thh').keyup(function() {
		var param = {
			'method' : 'update_thumb_size',
			'thw' : $('#thw').val(),
			'thh' : $('#thh').val()
		}
		var loading = $(this).closest('tr').find('.load');
		var msg = $(this).closest('table').find('.error');
		load_ajax('<?=Won::get('Config')->this_module?>', 'settings_controller', param, loading, msg);
	});	

	
});
</script>