<?php
Won::set(new Contact());
$list = Won::get('Contact')->getContacts();
?>

<?php foreach ($list as $contact) { ?>
<table>
	<tr>
    	<td width="60">Name :</td>
    	<td width="200"><input class="v" aid="<?=$contact['id']?>" style="width:200px" type="text" value="<?=$contact['name']?>" field="name" /></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
	<tr>
    	<td width="60">Phone :</td>
    	<td width="200"><input class="v" aid="<?=$contact['id']?>" style="width:200px" type="text" value="<?=$contact['phone']?>" field="phone" /></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Fax :</td>
    	<td width="200"><input class="v" aid="<?=$contact['id']?>" style="width:200px" type="text" value="<?=$contact['fax']?>" field="fax" /></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Address :</td>
    	<td width="200"><textarea class="v" aid="<?=$contact['id']?>" field="address" style="width:200px" rows="3"><?=$contact['address']?></textarea></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
    <tr>
    	<td width="60">Email :</td>
    	<td width="200"><input class="v" aid="<?=$contact['id']?>" style="width:200px" type="text" value="<?=$contact['email']?>" field="email" /></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
	<tr>
    	<td width="60">Website :</td>
    	<td width="200"><input class="v" aid="<?=$contact['id']?>" style="width:200px" type="text" value="<?=$contact['website']?>" field="website" /></td>
        <td class="load" width="16"></td>
        <td></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
    	<td colspan="2">
        	<button class="remove" aid="<?=$contact['id']?>">Remove</button>
        </td>
        <td class="load"></td>
    </tr>
</table>
<? } ?>

<script>
$('textarea').autogrow();
$('.remove').click(function() {
	var param = {
		method: 'remove',
		id : $(this).attr('aid')
	}
	var loading = $(this).closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading, function(data) {
			window.location.href = window.location.href;
		if (data=='') {
		} else {
			$('#msg').html(data);
		}
	});	
});
$('.v').keyup(function(){
	var param = {
		method: 'update',
		id : $(this).attr('aid'),
		key : $(this).attr('field'),
		value : $(this).val()
	}
	var loading = $(this).closest('tr').find('.load');
	load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading, '#msg');	
});
</script>