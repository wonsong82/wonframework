<h1>3. DB Handler Setup</h1>
<p>DB Handler is for backup/restore the database.</p>
<table style="width:500px">
	<?php foreach($dbhandler as $name=>$f):?>
    
    <tr>
    	<td width="180"><?php echo $f['name'];?></td>
        <td><input name="dbhandler_<?php echo $name;?>" type="text" value="<?php echo $f['val'];?>" /></td>
    </tr>
    
    <?php if(isset($_POST['data'])&&!$f['status']):?>
    <tr>
    	<td colspan="2" class="alert">└ <?php echo $f['error'];?></td>
    </tr>
    <?php endif;?>        
    <?php endforeach;?>   
    	
</table>
<br/>