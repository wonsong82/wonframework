<?php
if (!defined('CONFIG_LOADED'))
	require_once '../../config.php';

$banner = new Banner();
$banners = $banner->get_banners();


?>
<h1>Banners</h1>
<br/>

<div id="form">
<?php if (count($banners)) { foreach ($banners as $banner) { ?>

<table class="list" rowid="<?=$banner['id']?>">
	
    <tr>
    	<td><img src="<?=$banner['imgpath']?>" width="300" /></td>
        
        <td valign="top" style="padding-left:10px">
        	<div>Title</div>
            <div><input class="update" rowid="<?=$banner['id']?>" key="title" value="<?=htmlspecialchars($banner['title'])?>" style="width:200px" /></div>
            
            <div>Description</div>
            <div><textarea class="update" rowid="<?=$banner['id']?>" key="desc" rows="2" style="width:200px"><?=htmlspecialchars($banner['desc'])?></textarea></div>
            
            <div>Link</div>
            <div><input class="update" rowid="<?=$banner['id']?>" key="link" value="<?=htmlspecialchars($banner['link'])?>" style="width:200px" /></div>
            
            <div class="loading"></div>
        </td>
        
    </tr>
    
    <tr>
    	<td><div style="width:300px;overflow:hidden"><?=str_replace(SITE_URL, '', $banner['imgpath'])?></div></td>
        <td align="right" valign="bottom">   	
			
        	<input class="<?=!$banner['first']? 'moveup' : 'disabled'?>" rowid="<?=$banner['id']?>" type="button" value="▲" />
            
            <input class="<?=!$banner['last']? 'movedown' : 'disabled'?>" rowid="<?=$banner['id']?>" type="button" value="▼" />
            
            <input class="remove" rowid="<?=$banner['id']?>" type="button" value="Remove" />
        </td>
    </tr>  
    
</table>
<?php } } ?>

	<div id="add-new">
    <form id="new-file-form">
    	<input type="button" id="new-btn" value="Add New" />               
        <input type="file" id="new-file" name="bannerimage" accept="image/x-png,image/jpeg,image/gif" />        
        <input type="button" id="new-ok" value="OK" />    
    </form>
    </div>

</div>


<script>
$(function(){
	// get the module dir for ajax
	var module = $('#content').attr('module');
		
	
	///////////////////////////////////////////////////////////////////////	
	// add
	$('#new-file, #new-ok').css('display','none');
	
	$('#new-btn').click(function(){
		$(this).css('display','none');
		$('#new-file').css('display','inline-block');		
	});
	
	$('#new-file').change(function(){
		$('#new-ok').css('display','inline-block');
	});	
		
	// create iframe..
	var iframe = $('<iframe></iframe>');
	iframe.attr('id', 'upload_iframe');
	iframe.attr('name', 'upload_iframe');
	iframe.attr('width',0);
	iframe.attr('border',0);
	iframe.attr('height',0);
	iframe.css({'width':0, 'height':0, 'border':'none'});
	$('#new-file-form').parent().append(iframe);
		
	//set form
	var form = $('#new-file').parent();
	form.attr('target', 'upload_iframe');
	form.attr('action' , module + '/controllers/add.php');
	form.attr('method' , 'post');
	form.attr('enctype' , 'multipart/form-data');
	form.attr('encoding' , 'multipart/form-data');
	
	//set button
	$('#new-ok').click(function(){
		$('#msg').html(loading);
		form.submit();
		return false;
	});
	
	iframe.load(function(){
		$('#msg').html(iframe.contents().find('body').html());
		refresh_page();
	});


	
// Remove ///////////////////////////////////////////////////
	$('.remove').click(function(){
		
		// list : the cloest parent .list
		var list = $(this).closest('.list');
		var status = list.find('.loading');
		var id = $(this).attr('rowid');
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/remove.php',
			cachie : false,
			type : 'post',
			data : {
				id : id
			},
			success: function(data) {
				status.html('');
				$('#msg').html(data);
				list.stop(true).animate({'opacity':0},200,function(){
					refresh_page();
				});				
			}
		});
	});



	////////////////////////////////////////////////////////////
	// update 
	// ajax update when key pressed
	$('.update').keyup(function(e){
		var list = $(this).closest('.list');
		var status = list.find('.loading');
		var id = $(this).attr('rowid');
		var key = $(this).attr('key');
		var value = $(this).val();
		
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/update.php',
			cache : false,
			type : 'post',
			data : {
				id : id,
				key : key,
				value : value
			},
			success : function(data) {
				status.html('');
				$('#msg').html(data);							
			}
		});
	});
	
	
	
	///////////////////////////////////////////////////////
	// move up & move down
	$('.moveup').click(function(){
		var list = $(this).closest('.list');
		var status = list.find('.loading');
		id = $(this).attr('rowid');
		prev_id = list.prev().attr('rowid');				
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/swap.php',
			cache : false,
			type : 'post',
			data : {
				id1 : id,
				id2 : prev_id
			},
			success : function(data) {
				status.html('');
				$('#msg').html(data);				
				refresh_page();
			}
		});		
	});
	$('.movedown').click(function(){
		var list = $(this).closest('.list');
		var status = list.find('.loading');
		id = $(this).attr('rowid');
		next_id = list.next().attr('rowid');				
		status.html(loading);
		
		$.ajax({
			url : module + '/controllers/swap.php',
			cache : false,
			type : 'post',
			data : {
				id1 : id,
				id2 : next_id
			},
			success : function(data) {
				status.html('');
				$('#msg').html(data);
				refresh_page();
			}
		});
	});
	
	
});

function refresh_page() {
	// get the module dir for ajax
	var module = $('#content').attr('module');
	
	$('#content').html(loading);
	
	$.ajax({
		url : module + '/admin_menu.php' ,
		type : 'post',
		async : false,
		cache : false,
		success : function(data) {
			$('#content').html(data);
		}
	});
}
</script>