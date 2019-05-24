<h1>2. MySQL Database Setup</h1>
<table>
	<?php foreach($db as $name=>$value):?>
    
    <tr>
    	<td width="80"><?php echo ucwords($name);?></td>
        <td><input name="db_<?php echo $name;?>" type="text" value="<?php echo $value;?>" /></td>
    </tr>
        
    <?php endforeach;?>
    
	<?php if(isset($_POST['data'])&&!$dbPassed):?>
    <tr>
    	<td colspan="2" class="alert">└ Wrong Database Information.</td>
    </tr>
    <?php endif;?>
    
</table>
<br/>