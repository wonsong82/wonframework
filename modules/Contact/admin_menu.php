<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../config.php';

$contact = new Contact();
?>

<h1>Contact Information</h1>
<br/>

<div id="contact-form">
<table>
    <tr>
        <td><label for="name">Name: </label></td>
        <td><input type="text" name="name" id="name" size="30" value="<?=$contact->name?>" /></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td><label for="phone_a">Phone: </label></td>
        <td>(<input type="text" name="phone_a" id="phone_a" size="3" maxlength="3" value="<?=$contact->phone_a?>" />) 
       <input type="text" name="phone_f" id="phone_f" size="3" maxlength="3" value="<?=$contact->phone_f?>" /> - 
        <input type="text" name="phone_l" id="phone_l" size="4" maxlength="4" value="<?=$contact->phone_l?>" /></td>
    </tr>
    <tr>
        <td><label for="fax">Fax: </label></td>
         <td>(<input type="text" name="fax_a" id="fax_a" size="3" maxlength="3" value="<?=$contact->fax_a?>" />) 
       <input type="text" name="fax_f" id="fax_f" size="3" maxlength="3" value="<?=$contact->fax_f?>" /> - 
        <input type="text" name="fax_l" id="fax_l" size="4" maxlength="4" value="<?=$contact->fax_l?>" /></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td><label for="street">Address</label></td>            
    </tr>
    <tr>
        <td><label for="street">Street:</label></td>
        <td><input type="text" name="street" id="street" size="30" value="<?=$contact->street?>" /> (Apt# <input type="text" name="apt" id="apt" size="8" value="<?=$contact->apt?>" />)
    </tr>
     <tr>
        <td><label for="city">City:</label></td>
        <td><input type="text" name="city" id="city" size="10" value="<?=$contact->city?>" />
    </tr>
     <tr>
        <td><label for="state">State:</label></td>
        <td><input type="text" name="state" id="state" size="2" maxlength="2" value="<?=$contact->state?>" />
    </tr>
     <tr>
        <td><label for="zip">Zip:</label></td>
        <td><input type="text" name="zip" id="zip" size="10" maxlength="10" value="<?=$contact->zip?>" /></td>
    </tr>
    <tr>
    	<td><label for="country">Country:</label></td>
        <td><input type="text" name="country" id="country" size="10" maxlength="20" value="<?=$contact->country?>" /></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td> <label for="email">Email: </label></td>
        <td><input type="text" name="email" id="email" size="30" value="<?=$contact->email?>" /></td>
    </tr>
    <tr>
        <td> <label for="website">Website: </label></td>
        <td><input type="text" name="website" id="website" size="30" value="<?=$contact->website?>" /></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>
</div>

<script>
$(function(){
	// get the module dir for ajax
	var module = $('#content').attr('module');
	
	// ajax update when key pressed
	$('#contact-form input').keyup(function(e){
		
		$('#msg').html(loading);
		
		var key = $(this).attr('name');
		var value = $(this).val();
		
		$.ajax({
			url : module + '/controllers/update.php',
			cache : false,
			type : 'post',
			data : {
				key : key,
				value : value
			},
			success : function(data) {
				$('#msg').html(data);				
			}
		});
	});	
});


</script>

