<?php
$events = $this->Events->getEvents();
?>
<!-- add new top -->
<table>
	<tr>
    	<td width="150"><h3>New Event (Event Title) : </h3></td>
        <td><input type="text" class="new-title" autofocus="autofocus"/></td>
        <td class="load" width="16"></td>
        <td width="40"><button class="new-btn">Add</button></td>
    </tr>	
</table>
<!-- end add new top -->


<table>
	<tr>
    	<td valign="top">
        	<ul class="sort">
            	<?php foreach ($events as $event) {?>
                <li class="ui-state-default" rowid="<?=$event['id']?>">
                	<table>
                    	<tr>
                        	<td><?=$event['title']?></td>
                            <td width="20" class="load"></td>
                            <td width="50"><a class="detail button" href="<?=$this->Config->admin_url.'/'.$this->Config->this_module.'/'.$this->Config->this_module_page.'/edit='.$event['id']?>">Edit</a></td>
                            <td width="50"><button class="remove" rowid="<?=$event['id']?>">Remove</button></td>
                        </tr>
                    </table>
                </li>
                <?php } ?>
            </ul>
        </td>
    </tr>
</table>

<script>
$(function(){
	// add
	$('.new-btn').click(function(){
		var loading = $(this).closest('tr').find('.load');
		var args = [$(this).closest('tr').find('.new-title').val()];
		ajax('Events','addEvent',args, loading, function(e) {
			if (e.data) {
				$('#msg').html(e.data);
			} else {
				window.location.href = window.location.href;
			}
		});		
		return false;
	});
	$('.new-title').keypress(function(e) {
		if (e.keyCode==13) {
			var loading = $(this).closest('tr').find('.load');
			var args = [$(this).val()];
			ajax('Events','addEvent',args, loading, function(e) {
				if (e.data) {
					$('#msg').html(e.data);
				} else {
					window.location.href = window.location.href;
				}
			});
		}
	});
	
	// sortable
	$('.sort').sortable({
		'update' : function(event, ui) {
			var ids = [];
			$(event.target).find('li').each(function(){
				ids.push($(this).attr('rowid'));
			});
			ids = ids.join(',');
			var args = [ids];
			var loading = $(event.target).parent().find('.load');
			ajax('Events','sort',args,loading,function(e) {
				if (e.data) {
					$('#msg').html(e.data);
				}
			});
		}
	});
	$('.sort').disableSelection();
	
	// remove
	$('.remove').click(function(){
		var loading = $(this).closest('tr').find('.load');
		ajax('Events','removeEvent',[$(this).attr('rowid')],loading,function(e){
			if (e.data) {
				$('#msg').html(e.data);
			} else {
				window.location.href=window.location.href;
			}
		});
		return false;
	});
});
</script>