<?php
Won::set(new Contact());
?>
<table id="contact">
    <tr>
        <td><label for="name">Name</label></td>
        <td colspan="5"><input type="text" name="name" id="name" value="<?=Won::get('Contact')->name?>" /></td>
        <td width="20" class="load"></td>
        <td></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr>  
        <td width="60"><label for="phone_a">Phone</label></td>
        <td width="50"><input type="text" name="phone_a" id="phone_a" maxlength="3" value="<?=Won::get('Contact')->phone_a?>" /> </td>
        <td width="5">-</td>
        <td width="50"><input type="text" name="phone_f" id="phone_f" maxlength="3" value="<?=Won::get('Contact')->phone_f?>" /></td>
        <td width="5">-</td>
        <td width="50"><input type="text" name="phone_l" id="phone_l" maxlength="4" value="<?=Won::get('Contact')->phone_l?>" /></td>
    	<td width="20" class="load"></td>        
    </tr>
    
    <tr>
         <td><label for="fax">Fax: </label></td>
         <td><input type="text" name="fax_a" id="fax_a" maxlength="3" value="<?=Won::get('Contact')->fax_a?>" /></td>
         <td>-</td>
         <td><input type="text" name="fax_f" id="fax_f" maxlength="3" value="<?=Won::get('Contact')->fax_f?>" /></td>
         <td>-</td>
         <td><input type="text" name="fax_l" id="fax_l" maxlength="4" value="<?=Won::get('Contact')->fax_l?>" /></td>         
         <td class="load"></td>
    </tr>
    
    <tr><td>&nbsp;</td></tr>
    
    <tr>
        <td colspan="7"><label for="street">Address</label></td>            
    </tr>
    
    <tr>
        <td><label for="street">Street</label></td>
        <td colspan="5"><input type="text" name="street" id="street" value="<?=Won::get('Contact')->street?>" />
        <td class="load"></td>
    </tr>
    
    <tr>
        <td><label for="apt">Apt#</label></td>
        <td colspan="5"><input type="text" name="apt" id="apt" value="<?=Won::get('Contact')->apt?>" />
        <td class="load"></td>
    </tr>
    
     <tr>
        <td><label for="city">City:</label></td>
        <td colspan="5"><input type="text" name="city" id="city" value="<?=Won::get('Contact')->city?>" /></td>
        <td class="load"></td>      
    </tr>
     <tr>
        <td><label for="state">State</label></td>
        <td colspan="5"><input type="text" name="state" id="state" maxlength="2" value="<?=Won::get('Contact')->state?>" />
       <td class="load"></td>  
    </tr>
     <tr>
        <td><label for="zip">Zip</label></td>
        <td colspan="5"><input type="text" name="zip" id="zip" maxlength="10" value="<?=Won::get('Contact')->zip?>" /></td>
        <td class="load"></td>  
    </tr>
    <tr>
    	<td><label for="country">Country</label></td>
        <td colspan="5"><input type="text" name="country" id="country" maxlength="20" value="<?=Won::get('Contact')->country?>" /></td>
        <td class="load"></td>  
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td><label for="email">Email</label></td>
        <td colspan="5"><input type="text" name="email" id="email" value="<?=Won::get('Contact')->email?>" /></td>
        <td class="load"></td> 
    </tr>
    <tr>
        <td><label for="website">Website</label></td>
        <td colspan="5"><input type="text" name="website" id="website" value="<?=Won::get('Contact')->website?>" /></td>
        <td class="load"></td> 
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>


<script>
$(function(){
	// update
	$('#contact input').keyup(function(e){
		var param = {
			method : 'update',			
			key : $(this).attr('name'),
			value : $(this).val()
		};
		var loading_target = $(this).parent().next('.load');
		load_ajax('<?=Won::get('Config')->this_module?>', 'controller', param, loading_target, '#msg'); 
	});	
});
</script>

