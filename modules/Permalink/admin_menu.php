<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../config.php';

$permalink = new Permalink();
$links = $permalink->get_list();

?>

<h1>Permalink Information</h1>
<br/>

<div id="permalink-form">

<?php if (count($links)) { ?>
<table id="permalink-list">
	<thead>    
       	<td>Uri</td>
        <td>Title</td>
        <td>Template Path</td>
        <td></td>
    </thead>
    <?php foreach ($links as $link) { ?>
    <tr>
    	<td><input rowid="<?=$link['id']?>" key="uri" value="<?=$link['uri']?>" size="30" /></td>
        <td><input rowid="<?=$link['id']?>" key="title" value="<?=$link['title']?>" size="50" /></td>
        <td><input rowid="<?=$link['id']?>" key="template_path" value="<?=$link['template_path']?>" size="40" /></td>
        <td><input class="remove-button" rowid="<?=$link['id']?>" type="button" value="Remove" /></td>
        <td class="loading"></td>
    </tr>
    <?php } ?>   
</table>
<?php } ?>

<table id="add-new">
	<?php if (!count($links)) { ?>
    <thead>
    	<td>Uri</td>
        <td>Title</td>
        <td>Template Path</td>
        <td></td>
    </thead>
    <?php } ?>
    <tr>
    	<td colspan="5">Use $param_name for dynamic URI. For example. forum/$category/$title will get any URI starts with forum followed by parameters.</td>
    </tr>
	 <tr>
    	<td><input id="new-uri" key="uri" size="30" /></td>
        <td><input id="new-title" key="title" size="50" /></td>
        <td><input id="new-template-path" key="template_path" size="40" /></td>
        <td><input id="new-button" type="button" value="Add" /></td>        
        <td class="loading"></td>
    </tr>    
</table>   
    
</div>

<script>
$(function(){
	// get the module dir for ajax
	var module = $('#content').attr('module');
	
	
	// insert
	$('#add-new #new-button').click(function(){
		var status = $(this).parent().parent().find('.loading');
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/add.php',
			cache : false,
			type : 'post',
			data : {
				uri : $('#new-uri').val(),
				title : $('#new-title').val(),
				template_path : $('#new-template-path').val()
			},
			success : function(data) {
				status.html('');
				
				$('#msg').html(data);
				refresh_page();
			}
		});
			
		return false;
	});
	
	// remove 
	$('.remove-button').click(function(){
		var status = $(this).parent().parent().find('.loading');
		var id = $(this).attr('rowid');
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/remove.php',
			cachie : false,
			type : 'post',
			data : {
				id : id
			},
			success: function(data) {
				status.html('');
				$('#msg').html(data);
				refresh_page();
			}
		});
		
		return false;
	});
	
	// update 
	// ajax update when key pressed
	$('#permalink-list input').keyup(function(e){
		var status = $(this).parent().parent().find('.loading');
		status.html(loading);
		
		var id = $(this).attr('rowid');
		var key = $(this).attr('key');
		var value = $(this).val();					
		
		
		$.ajax({
			url : module + '/controllers/update.php',
			cache : false,
			type : 'post',
			data : {
				id : id,
				key : key,
				value : value
			},
			success : function(data) {
				status.html('');
				$('#msg').html(data);							
			}
		});
	});	
	
});

function refresh_page() {
	// get the module dir for ajax
	var module = $('#content').attr('module');
	
	$('#content').html(loading);
	
	$.ajax({
		url : module + '/admin_menu.php' ,
		type : 'post',
		async : false,
		cache : false,
		success : function(data) {
			$('#content').html(data);
		}
	});
}
</script>
