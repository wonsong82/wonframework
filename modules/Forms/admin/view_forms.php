<?php
$forms=$this->Forms->getForms();
$formName=null;
if (count($forms>0)) {
	if (!isset($p['view'])) {
		$formName=$forms[0];
	} 
	else {
		$formName=urldecode($p['view']);	
	}
}
$records=array();
if ($formName) {
	$records=$this->Forms->getRecords($formName);
}

$width=0;
if (count($records)>0 && count($records[0]['values'])>0) {
	$width= floor((430-(count($records[0]['values'])*10))/ count($records[0]['values']));
}
//var_dump($records);
?>
<table>
<tr>

<td width="160" valign="top">
	<ul class="nav">
    	<?php foreach ($forms as $fName) {?>
        <li<?=$formName==$fName? ' class="selected"' : '';?>>
        	<a href="<?=$this->Config->admin_url.'/'.$this->Config->this_module.'/'.$this->Config->this_module_page.'/view='.urlencode($fName)?>"><?=$fName?></a>
        </li>
        <?php } ?>
    </ul>
</td>

<td valign="top">
	<table>
    	<?php if (count($records>0)) {?>
        <thead>
        	<?php foreach ($records[0]['values'] as $fName=>$fVal) {?>
        	<td width="<?=$width?>">
            	<div style="overflow:hidden;width:<?=$width?>px;height:20px;"><?=$fName?></div>
            </td>
            <?php } ?>
            <td width="130"><div style="height:20px">Date</div></td>
            <td width="50"><div style="height:20px">Edit</div></td>
        </thead>
        <?php } ?>
        
    	<?php foreach($records as $record){?>
    	<tr height="25">
        	<?php foreach($record['values'] as $fName=>$fVal) {?>
            <td width="<?=$width?>">
            	<div style="overflow:hidden;width:<?=$width?>px;height:18px;"><?php
			if (is_bool($fVal)) {
				echo $fVal? 'Yes' : 'No';
			}
			else {
				echo $fVal;
			}
		?> 
               	</div>
            </td>
            <?php }?>
        	<td width="130"><?=date('Y-m-d h:i a',strtotime($record['date']))?></td>
            <td width="50"><a href="<?=$this->Config->admin_url.'/'.$this->Config->this_module.'/'.$this->Config->this_module_page.'/edit='.$record['id']?>" class="button">Edit</a></td>
            
        </tr>
        <?php } ?>
    </table>
</td>

</tr>
</table>