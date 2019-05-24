<?php
if($auth->error): ?>
<p style="color:#f00"><?=$auth->errorMsg?></p>
<?php endif;?> 
<form action="<?=$reg->config->db?>" method="post">
	Database Manager : Password Required:<br/>
	<input type="hidden" name="webwon_db_handler_userID" value="admin"/>
    <input type="password" name="webwon_db_handler_password" autocomplete="off"/>
    <input type="submit" />
</form>