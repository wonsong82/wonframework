<?php
if(isset($reg->req->post['filename']) && trim($reg->req->post['filename'])!=''){
	$reg->db->backup($reg->config->dbBackupDir.trim($reg->req->post['filename']));
	?>
    <script>
	alert("Backup Completed");
	window.location.href='<?=$reg->config->db?>';
	</script>
    <?
}


?>
<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="backup" />
    <input type="text" name="filename" value="<?=date('Y-m-d-His').'.sql';?>" />
    <input type="submit" value="Submit" />
</form>