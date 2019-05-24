<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../../config.php';

$table_editor = new TableEditor();
$tables = $table_editor->list_tables();

?>

<h1>Table Editor</h1>
<p class="alert">This editor can mess up your entire database. Please do not use this unless you know what you are doing.</p>
<br/>

<div>
	<select id="table-editor-select">
        <option value="">Choose Table</option>
        <?php foreach($tables as $table) { ?>
        <option value="<?=$table?>"><?=$table?></option>  
        <?php } ?>
    </select>  
</div>

<div id="table-editor-result">
</div>

<script>
	$(function(){	
			
	//table chooser
	$('#table-editor-select').change(function(){		
		var table_name = $(this).val();		
		if (table_name) {
			$('#table-editor-result').html('<img src="images/loading.gif" />');
			$.ajax({
				url : module_dir + '/TableEditor/admin/list_table.php',
				cache : false,
				type : 'post',
				data : {
					table_name : table_name
				},
				success : function(data) {
					$('#table-editor-result').html(data);
				}
			});
		}
	});		
});
</script>
