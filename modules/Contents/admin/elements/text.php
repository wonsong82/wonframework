
<table>
	<tr>
    	<td>
        	<textarea id="text" rows="10"><?=$element['value'];?></textarea>
        </td>
        <td width="16" class="load"></td>
    </tr>
</table>
<script>
$(function(){
	$('#text').autogrow();
	$('#text').keyup(function(){
		var param = {
			method:'update_value',
			element_id : <?=$id;?>,
			value: $('#text').val()
		}
		
		
		var loading = $(this).closest('table').find('.load');
		load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, loading, '#msg');
	});
});
</script>