<?php
if(isset($reg->req->post['filename']) && trim($reg->req->post['filename'])!=''){
	$reg->db->restore($reg->config->dbBackupDir.trim($reg->req->post['filename']));
	?>
    <script>
	alert("Restore Completed");
	window.location.href='<?=$reg->config->db?>';
	</script>
    <?
}
?>


<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="restore" />
    <input id="file" type="text" name="filename" value="" readonly="readonly" />
    <input type="submit" value="Submit" />
</form>

<script>
function changeValue(element){
	document.getElementById('file').value = element.innerHTML;	
}
</script>

<table>
<?php foreach(array_reverse(glob($reg->config->dbBackupDir.'*.*')) as $sql): ?>
	<tr><td onclick="changeValue(this)"><?=str_replace($reg->config->dbBackupDir,'',$sql);?></td></tr>
<?php endforeach;?>
</table>

<style>
td{
	background:#eee;
	cursor:pointer;
	padding:2px 10px;
}
</style>
