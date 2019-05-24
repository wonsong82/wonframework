<?php
$id = (int)$p['edit'];
$record = $this->Forms->getRecord($id);
?>
<table>
<?php foreach ($record['values'] as $name=>$val) {?>
	<tr>
    	<td><h3><?=$name?></h3></td>        
    </tr>
    
    <tr>
    	<td class="field <?=gettype($val)?>">
        	<input type="hidden" class="name" value="<?=$name?>"/>
        	<?php 
			if (is_string($val)) { ?>
            <textarea class="val autogrow" style="width:500px" rows="1"><?=$val?></textarea>
            <?php } 
			
			else if (is_bool($val)) {
				$inputName=str_replace(' ','',$name);				
			?>
            <label>Yes</label> <input name="<?=$inputName?>" type="radio" style="width:20px;"<?=$val? ' checked="checked"':''?> value="yes"/>
            <label>No</label> <input name="<?=$inputName?>" type="radio" style="width:20px;"<?=$val==false? ' checked="checked"':''?> value="no"/>
			<?php } ?>
            <input type="hidden" class="val" value="<?=$val? 'yes':'no'?>"/>
        </td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>    
   
<?php }?>
 <tr><td><button id="update" recordid="<?=$id?>" >Update</button></td>
 <td class="load"></td></tr>
    
    <tr><td>&nbsp;</td></tr>
</table>

<script>
$(function(){
	$('.autogrow').autogrow();
	
	$('.field.boolean').each(function(){
		$(this).find('input:radio').change(function(){
			$(this).closest('.field').find('.val').val($(this).val());
		});
	});

	$('#update').click(function(){
		var param={
			'method':'update_values',
			'record_id':<?=$id;?>			
		}
		$('.field').each(function(){
			param[$(this).find('.name').val()] = $(this).find('.val').val();
		});
		
		var loading = $(this).closest('tr').find('.load');
		load_ajax('<?=$this->Config->this_module?>', 'form_controller', param, loading, function(){
			window.location.href = '<?=$this->Config->admin_url.'/'.$this->Config->this_module.'/'.$this->Config->this_module_page.'/view='.urlencode($record['form_name'])?>';
		});	
		
	});
});



</script>