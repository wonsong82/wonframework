<span>Database Manager</span><br/>
<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="backup" />
    <input type="submit" value="Backup" />
</form>

<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="restore" />
    <input type="submit" value="Restore" />
</form>

<form action="<?=$reg->config->db?>" method="post">
	<input type="hidden" name="action" value="password" />
    <input type="submit" value="Change Password" />
</form>

<form action="<?=$reg->config->db?>" method="post">
    <input type="hidden" name="action" value="logout" />
    <input type="submit" value="Logout" />
</form>
<hr/>
<style>
form {display:inline;}
</style>
   	