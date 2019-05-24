<?php
$id = $params['edit'];
$event = $this->Events->getEvent($id);
$uploadDir = $this->Config->content_dir.'/uploads';
?>
<table>

<!-- title -->
<tr><td colspan="2"><h3>Event Name</h3></td></tr>
<tr><td><input id="e-title" type="text" value="<?=htmlspecialchars($event['title'])?>"/></td>
	<td width="16" class="load"></td></tr>

<tr><td colspan="2"><h3>Url Friendly Name</h3></td></tr>
<tr><td colspan="2"><h2 id="e-url-friendly-title"><?=$event['url_friendly_title']?></h2></td></tr>
<tr><td>&nbsp;</td></tr><!-- end title-->

<!-- dates -->
<tr><td colspan="2"><h3>Event Dates</h3></td></tr>
<tr><td><input id="e-start-date" class="datepicker" type="text" value="<?=$event['start_date']?>" style="width:100px"/>
		<input id="e-end-date" class="datepicker" type="text" value="<?=$event['end_date']?>" style="width:100px"/></td>
    <td width="16" class="load"></td></tr>
<tr><td>&nbsp;</td></tr><!-- end dates-->

<!-- content -->
<tr><td colspan="2"><h3>Event Content</h3></td></tr>
<tr><td><textarea rows="10" id="e-content"><?=htmlspecialchars($event['content'])?></textarea></td>
	<td width="16" class="load" valign="top"></td></tr>
<tr><td>&nbsp;</td></tr><!-- end -->

<!-- images -->
<tr><td colspan="2"><h3>Images</h3></td></tr>
<tr><td>
	<div id="image-container">
    	<ul class="image-sortable">
        <?php foreach ($event['images'] as $img) { ?>
        	<li imageid="<?=$img['id']?>">
            	<img src="<?=$img['thumb']?>"/>
                <div class="control" style="text-align:right;padding-bottom:5px;position:absolute;top:7px;right:7px;display:none;">
                	<button class="edit-btn" imageid="<?=$img['id']?>">Edit</button>                    
                    <button class="del-btn" imageid="<?=$img['id']?>">Del</button>
                    <button style="margin-top:2px" class="edit-thumb-btn" imageid="<?=$img['id']?>">Thumbnail</button>
                </div>
            </li>
        <?php } ?>
        </ul>
    </div>
    <div style="clear:both">
    	<button id="add-image-btn">Add Image</button>
    </div>
	</td>
	<td width="16" class="load" valign="top"></td></tr>
<tr><td>&nbsp;</td></tr><!-- end -->

</table>


<style>
#image-container li {
	float:left; width:100px;  background:#cccccc;padding:5px;margin:3px; cursor:move; position:relative;
}
#video-container li {
	float:left; width:100px;  background:#cccccc;padding:5px;margin:3px; cursor:move; position:relative;
}
#video-container li img {
	width:100px;
}
</style>

<script>
$(function(){

//title
$('#e-title').keyup(function(){
	var loading = $(this).closest('tr').find('.load');
	ajax('Events','updateTitle', [<?=$id?>,$(this).val()],loading,function(e){
		if (e.data.title) {
			$('#e-url-friendly-title').html(e.data.title);
		}
	});
});

//dates
$('.datepicker').datepicker({
	'dateFormat' : 'yy-mm-dd'
});
$('.datepicker').change(function(){
	var loading = $(this).closest('tr').find('.load');
	var args = [<?=$id?>,$('#e-start-date').val(),$('#e-end-date').val()];
	ajax('Events','updateDates',args,loading,function(e){
		if (e.data==false) {
			$('#msg').html("Starting date cannot be later than Ending date");
		} else if (e.data==null) {
			$('#msg').html('');
		}
	});
});

//content
$('#e-content').autogrow();
$('#e-content').keyup(function(){
	var loading=$(this).closest('tr').find('.load');
	var args = [<?=$id?>,$(this).val()];
	ajax('Events','updateContent',args,loading,function(e){
		if (e.data) {
			$('#msg').html(e.data);
		}
	});
});

//images upload
function imgUploaded(data,target){
	var loading=$(this).closest('tr').find('.load');
	var args = [<?=$id?>,'<?=$uploadDir?>'+'/'+data,100,100];
	ajax('Events','addImage',args,loading,function(e) {
		if (e.data) {
			$('#msg').html(e.data);
		} else {
			window.location.href=window.location.href;
		}
	});
}
$('#add-image-btn').click(function(){
	<?=$this->File->uploader($uploadDir,'image/jpeg','imgUploaded');?>
});

//image sort
$('.image-sortable').sortable({
	'update':function(event,ui){
		var ids=[];
		$(event.target).find('>li').each(function(){
			ids.push($(this).attr('imageid'));
		});
		ids=ids.join(',');
		var loading=$(this).closest('tr').find('.load');
		ajax('Events','sortImages',[<?=$id?>,ids],loading,function(e){
			if (e.data) {
				$('#msg').html(e.data);
			}
		});
	}
});

//image controls
$('#image-container li').hover(function(){
	$(this).find('.control').css('display','block');
},function(){
	$(this).find('.control').css('display','none');
});

//edit image
function imgEdited(data,target){
	var loading=$(this).closest('tr').find('.load');
	var args = [data.id, data.width, data.height, data.x, data.y, data.x2, data.y2];		
	ajax('Images','updateImage',args,loading,function(e) {
		if (e.data) {
			$('#msg').html(e.data);
		} else {
			window.location.href=window.location.href;
		}
	});
}
<?php foreach ($event['images'] as $img) {?>
$('#image-container li .edit-btn[imageid="<?=$img['id']?>"]').click(function(){
	<?=$this->Images->imageEditor($img['id'],'imgEdited');?>
});
<?php } ?>

//edit thumb
function thmEdited(data,target){
	var loading=$(this).closest('tr').find('.load');
	var args = [data.id, data.width, data.height, data.x, data.y, data.x2, data.y2];	
	ajax('Images','updateThumb',args,loading,function(e) {
		if (e.data) {
			$('#msg').html(e.data);
		} else {
			window.location.href=window.location.href;
		}
	});
}
<?php foreach ($event['images'] as $img) {?>
$('#image-container li .edit-thumb-btn[imageid="<?=$img['id']?>"]').click(function(){
	<?=$this->Images->thumbEditor($img['id'],'thmEdited');?>
});
<?php } ?>

//delete image
$('#image-container li .del-btn').click(function(){
	var loading=$(this).closest('tr').find('.load');
	var args = [<?=$id?>, $(this).attr('imageid')];		
	ajax('Events','removeImage',args,loading,function(e) {
		if (e.data) {
			$('#msg').html(e.data);
		} else {
			window.location.href=window.location.href;
		}
	});	
});

});
</script>

