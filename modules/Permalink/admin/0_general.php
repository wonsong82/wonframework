<?php
Won::set(new Permalink());
$links = Won::get('Permalink')->get_list();
?>

<?php if (count($links)) { ?>
<table id="permalink-list">
	<thead>    
       	<td>Uri</td>
        <td>Title</td>
        <td>Template Path</td>
        <td width="20">&nbsp;</td>
        <td width="70">&nbsp;</td>
    </thead>
    <?php foreach ($links as $link) { ?>
    <tr>
    	<td><input rowid="<?=$link['id']?>" key="uri" value="<?=$link['uri']?>" /></td>
        <td><input rowid="<?=$link['id']?>" key="title" value="<?=$link['title']?>" /></td>
        <td><input rowid="<?=$link['id']?>" key="template_path" value="<?=$link['template_path']?>" /></td>
        <td class="load"></td>
        <td><button class="remove-button" rowid="<?=$link['id']?>">Remove</button></td>        
    </tr>
    <?php } ?>   
</table>
<?php } ?>

<table id="add-new">   
    <tr>
    	<td colspan="5">Use $param_name for dynamic URI. For example. forum/$category/$title will get any URI starts with forum followed by parameters.</td>
    </tr>
    <tr>
    	<td>Uri</td>
        <td>Title</td>
        <td>Template Path</td>
        <td width="20">&nbsp;</td>
        <td width="70">&nbsp;</td>
    </tr>
	 <tr>
    	<td><input id="new-uri" key="uri"/></td>
        <td><input id="new-title" key="title"/></td>
        <td><input id="new-template-path" key="template_path"/></td>
        <td class="load"></td>
        <td><button class="add-button">Add</button></td>          
    </tr>    
</table>   
    


<script>
$(function(){
	
	// insert new	
	$('#add-new .add-button').click(function(){
		var param = {
			method : 'add',
			uri : $('#new-uri').val(),
			title : $('#new-title').val(),
			template_path : $('#new-template-path').val()
		};		
		var loading_target = $(this).parent().prev('.load');		
		load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading_target, 'refresh'); 
	});
	
	// remove	
	$('.remove-button').click(function(){
		var param = {
			method : 'remove',
			id : $(this).attr('rowid')
		};		
		var loading_target = $(this).parent().prev('.load');		
		load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading_target, 'refresh'); 
	});
	
	// update
	$('#permalink-list input').keyup(function(e){
		var param = {
			method : 'update',
			id : $(this).attr('rowid'),
			key : $(this).attr('key'),
			value : $(this).val()
		};
		var loading_target = $(this).parent().next('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading_target, '#msg'); 
	});		
});
</script>
