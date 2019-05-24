<?php
Won::set(new File());
$uploadDir = Won::get('Config')->content_dir . '/uploads';
Won::set(new Settings());
$logo = Won::get('Settings')->getSetting('Core','siteLogo');

?>

<table>
	<tr><td><h1>Site Logo</h1></td></tr>
    <tr><td><img id="logoimg" src="<?=Won::get('Config')->content_url?>/uploads/<?=$logo?>" height="100" src="" title="Click to add/change" /></td>
</table>

<style>
	#logoimg {
		cursor:pointer;
	}
</style>

<script>
function imageUploaded(data, target){
	// ajax to image creator
	var param = {
		method : 'set_logo',
		logo : data
	};
	load_ajax('<?=Won::get('Config')->this_module;?>', 'site_controller', param, null, function(status) {
		if (status=='') {
			window.location.href = window.location.href;
		} 
		else {
			$('#msg').html(status);
		}
	});
}


$('#logoimg').click(function(){
	<?=Won::get('File')->uploader($uploadDir, 'image/jpeg', 'imageUploaded');?> 
});

</script>