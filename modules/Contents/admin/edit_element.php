<?php
$id = $params['id'];
$type = $params['type'];
$element = Won::get('Contents')->get_element($id);

?>
<table>
	<tr>
    	<td colspan="2"><h1>Edit: <?=ucwords($type);?> Element</h1></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
    	<td><h3>Title</h3></td>
    </tr>   
	<tr>
    	<td><input type="text" id="title" value="<?=$element['title'];?>" /></td>
        <td class="load" width="16">        
    </tr>     
    <tr><td>&nbsp;</td></tr>
    
    <tr>
    	<td colspan="2">
        	<?php include dirname(__FILE__).'/elements/' . $type . '.php'; ?>
        </td>
    </tr>
     
</table>
<?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
	<a href="<?=$_SERVER['HTTP_REFERER'];?>" class="button">< GO Back</a>
<?php } ?>

<script>
$(function(){
	$('#title').keyup(function(){
		var param = {
			method : 'update_title',
			element_id : <?=$id;?>,
			title : $('#title').val()
		}
		var loading = $(this).closest('tr').find('.load');
		load_ajax('<?=Won::get('Config')->this_module;?>', 'element_controller', param, loading, '#msg');
	});
});
</script>

