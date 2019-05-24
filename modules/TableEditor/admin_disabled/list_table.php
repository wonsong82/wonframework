<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

// load class
$table_editor = new TableEditor();

// get table name
$table_name = $_POST['table_name'];

// get the table
$fields = $table_editor->get_table_structure($table_name);
$table = $table_editor->get_table($table_name);

?>

<?php if ($table) { ?>
<table id="list">	
	<thead>    	
		<?php if ($fields) { foreach ($fields as $field) { if ($field!='id') { ?>
        <td><?=$field?></td>
        <?php }}} ?>    	
    </thead>	
    
    <?php foreach ($table as $row) { $id=$row['id']; ?>
    <tr>    	
    	<?php foreach ($row as $key=>$val) { if ($key!='id') { ?>
        <td><input class="field" type="text" rowid="<?=$id?>" key="<?=$key?>" value="<?=htmlspecialchars($val)?>" table="<?=$table_name?>" /></td>
        <?php } } ?>
        <td><input class="del" type="button" rowid="<?=$id?>" value="Remove" table="<?=$table_name?>" /></td>
        <td class="status"></td>
    </tr>
    <?php } ?> 
</table>
<?php } ?>

<table id="add-new">
	<?php if (!$table) { ?>
    <thead>
    	<?php if ($fields) { foreach ($fields as $field) { if ($field!='id') { ?>
        <td><?=$field?></td>
        <?php }}} ?>  
    </thead>
    <?php } ?>
    <tr>
		<?php if($fields) { foreach ($fields as $field) { if ($field!='id') { ?>
        <td><input class="field" type="text" key="<?=$field?>" /></td>
        <?php } } } ?>
        <td><input id="new-button" type="button" table="<?=$table_name?>" value="Add"/>
        <td class="status"></td>
    </tr>    
    
</table>

<script>
$(function(){
	// get the module dir for ajax
	var module = $('#content').attr('module');
	
	// update
	$('#list .field').keyup(function(e){
		var status = $(this).parent().parent().find('.status');		
		status.html(loading);
		
		var table = $(this).attr('table');
		var id = $(this).attr('rowid');
		var key = $(this).attr('key');
		var val = $(this).attr('value');
		
		$.ajax({
			url : module_dir + '/' + module + '/admin/admin_controller.php',
			type : 'post',
			cache : false,
			data : {
				method : 'update',
				table : table,
				id : id,
				key : key,
				val : val
			},
			success : function(data) {
				status.html('');
			}
		});			 
	});
	
	// insert
	$('#add-new #new-button').click(function(){
		var status = $(this).parent().parent().find('.status');
		status.html(loading);
		
		var param = {};
		$(this).parent().parent().find('.field').each(function(){
			param[$(this).attr('key')] = $(this).val();
		});
		var table = $(this).attr('table');
		param.table = table;
		param.method = 'add';
		
		$.ajax({
			url : module_dir + '/' + module + '/admin/admin_controller.php',
			cache : false,
			type : 'post',
			data : param,
			success : function(data) {
				status.html('');
				refresh_page(table);
			}
		});			
	});
	
	
	
	$('.del').click(function(){
		var status = $(this).parent().parent().find('.status');		
		status.html(loading);
		
		var table = $(this).attr('table');
		var id = $(this).attr('rowid');
		
		$.ajax({
			url : module_dir + '/' + module + '/admin/admin_controller.php',
			type : 'post',
			cache : false,
			data : {
				method : 'remove',
				table : table,
				id : id
			},
			success : function(data) {
				status.html('');
				refresh_page(table);
			}
		});
	});	
});

function refresh_page(table) {
	// get the module dir for ajax
	var module = $('#content').attr('module');	
	$('#table-editor-result').html(loading);	
	
	$.ajax({
		url : module_dir + '/' + module + '/admin/list_table.php',
		cache : false,
		type : 'post',
		data : {
			table_name : table
		},
		success : function(data) {
			$('#table-editor-result').html(data);
		}
	});	
}
	
	
</script>

