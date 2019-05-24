<table>
	<tr><td colspan="3">Enter your desinated URI</td></tr>
	<tr>
    	<td width="30"><h2>URI</h2></td>
        <td width="300"><input id="uri" type="text" autofocus="autofocus" /></td>
        <td width="16" class="load"></td>
        <td></td>
    </tr>
    
    <tr><td colspan="4" class="error"></td>
    <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2"><Button id="submit">Add</Button></td>
    </tr>   
</table>

<script>
$(function(){
	
	// Add
	$('#submit').click(function(){	
		var param = {
			method : 'add',
			uri : $('#uri').val()
		}
		var table = $(this).closest('table');
		var loading = table.find('.load');
		var msg = table.find('.error');
		msg.html('');
		
		load_ajax('<?=Won::get('Config')->this_module;?>', 'page_controller', param, loading, function(data) {	
			if (data != '')
				msg.html(data);		
			else
				window.location.href = '<?=Won::get('Config')->admin_url .'/'.Won::get('Config')->this_module;?>';	
		});
	});
	
	// Add
	$('#uri').keyup(function(e){
		if (e.keyCode == 13) {	
			var param = {
				method : 'add',
				uri : $('#uri').val()
			}
			var table = $(this).closest('table');
			var loading = table.find('.load');
			var msg = table.find('.error');
			msg.html('');
			
			load_ajax('<?=Won::get('Config')->this_module;?>', 'page_controller', param, loading, function(data) {	
				if (data != '')
					msg.html(data);		
				else
				window.location.href = '<?=Won::get('Config')->admin_url .'/'.Won::get('Config')->this_module;?>';	
			});
		}
	});
});
</script>