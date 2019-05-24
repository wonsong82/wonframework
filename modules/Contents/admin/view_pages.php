<?php
$pages = Won::get('Contents')->get_pages();
?>

<table>
	<thead>
    	<td></td>
        <td width="16" class="load"></td>
        <td width="40"></td>
        <td width="70"></td>
    </thead>
    <?php foreach ($pages as $page) { ?>
    	<tr>
        	<td><?=str_replace('/' , ' > ' , $page['uri']);?></td>
            <td class="load"></td>
            <td><a class="button" href="<?=Won::get('Config')->admin_url .'/'. Won::get('Config')->this_module .'/' . Won::get('Config')->this_module_page . '/edit=page&id=' .$page['id']?>">Edit</a></td>
            <td><button rowid="<?=$page['id'];?>" class="remove">Remove</button></td>
        </tr>
    <?php } ?>
</table>

<style>
tr td {padding:5px}
tr+tr td {border-top:1px dotted #dddddd}
</style>

<script>
$(function(){
	$('.remove').click(function(){
		var param = {
			method : 'remove',
			id : $(this).attr('rowid')
		}
		var loading = $(this).parent().parent().find('.load');			
		
		load_ajax('<?=Won::get('Config')->this_module?>', 'page_controller', param, loading, function(data){			
			if (data=='') {
				loading.parent().hide(1000);
			}
		});
		return false;
	});	
});
</script>