<?php
if (isset($reg->req->post['curpass']) && isset($reg->req->post['password']) && isset($reg->req->post['password2'])){
	$curPass = $reg->req->post['curpass'];
	$newPass = $reg->req->post['password'];
	$newPass2 = $reg->req->post['password2'];
	if(!$hash->checkHash($curPass, DBPASS)){
		echo '<p style="color:#f00">Current password is wrong.</p>';
	}
	elseif(trim($newPass)==''||$newPass!=$newPass2){
		echo '<p style="color:#f00">New passwords do not patch.</p>';
	}
	else{
		$newPassEncoded = $hash->getHash($newPass);
		file_put_contents($reg->config->dbDir . 'config.php', 
		'<?php'."\n".
		"define('DBPASS','".$newPassEncoded."');"."\n".
		'?>');
		echo '<p style="color:#00f">Password changed.</p>';
	}
}
?>

<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="password" />
    
    <span>Current Password</span><br/>
    <input type="password" name="curpass"/><br/><br/>
    
    <span>New Password</span><br/>
    <input type="password" name="password"/><br/>
    <span>Repeat Password</span><br/>
    <input type="password" name="password2"/><br/>
    
    <br/>
    <input type="submit" value="Submit" />
</form>