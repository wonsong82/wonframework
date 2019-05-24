<?php
$link_text = '';
$link_value = '';
if ($element['value'] != '') {
	$link = unserialize($element['value']);
	if (isset($link['text']))
		$link_text = $link['text'];
	if (isset($link['href']))
		$link_value = $link['href'];
}
?>

<table>
	<tr>
    	<td>
        	Link Anchor Text
        	<input type="text" id="link_text" value="<?=$link_text;?>" />
            Link Location
            <input type="text" id="link_href" value="<?=$link_value;?>" />
        	</td>
        <td width="16" class="load"></td>
    </tr>
</table>
<script>
$(function(){
	$('#link_text, #link_href').keyup(function(){
		
		var param = {
			method:'update_link',
			element_id:<?=$id;?>,
			text : $('#link_text').val(),
			href : $('#link_href').val()
		}
		
		var loading = $(this).closest('table').find('.load');
		load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, loading, '#msg');
	});
});
</script>