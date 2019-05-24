<h1>4. Site Setup</h1>
<table style="width:500px">
	<?php foreach($site as $name=>$f):?>
    
    <tr>
    	<td width="180"><?php echo $f['name'];?></td>
        <td><input name="site_<?php echo $name;?>" type="text" value="<?php echo $f['val'];?>" /></td>
    </tr>
    
    <?php if(isset($_POST['data'])&&!$f['status']):?>
    <tr>
    	<td colspan="2" class="alert">└ <?php echo $f['error'];?></td>
    </tr>
    <?php endif;?>        
    <?php endforeach;?>
    
    <tr>
    	<td>Timezone</td>
        <td>
        	<select name="timezone">
			<?php foreach ($timezones as $zone=>$text):?>
            	<option<?php echo $timezone==$zone?' selected="selected"':''?> value="<?php echo $zone;?>"><?php echo $text;?></option>
			<?php endforeach;?>
            </select>
        </td>
    </tr>	
</table>
<br/>