<h1>1. Requirements</h1>
<?php foreach($check as $name=>$val): ?>

<table>
	<tr>
    	<td colspan="2" align="center"><h1><?php echo $name;?></h1></td>
    </tr>
    
    <?php foreach($val['data'] as $field): ?>
    
    <tr>
    	<td class="fieldname" width="350"><?php echo $field['name'];?></td>
        <?php if($field['status']): ?>
        <td class="fieldstatus">YES</td>
        <?php else :?>
        <td class="fieldstatus error">NO</td>
        <?php endif;?>
    </tr>
    
    <?php if(!$field['status']):?>
    <tr>
    	<td colspan="2" class="alert">└ <?php echo $field['error_msg'];?></td>
    </tr>
    <?php endif;?>
    
    <?php endforeach; ?>
    
</table>


<?php endforeach; ?>
<br/>