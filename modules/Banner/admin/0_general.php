<?php
Won::set(new Banner());
$banners = Won::get('Banner')->get_banners();
?>


<?php if (count($banners)) {?> 
	<?php foreach ($banners as $banner) { ?>

<table rowid="<?=$banner['id']?>">
	
    <tr>
    	<td width="300"><img src="<?=$banner['imgpath']?>" width="300" /></td>
        
        <td valign="top" style="padding-left:10px">
        	<div>Title</div>
            <div><input class="update" rowid="<?=$banner['id']?>" key="title" value="<?=htmlspecialchars($banner['title'])?>"/></div>
            
            <div>Description</div>
            <div><textarea class="update" rowid="<?=$banner['id']?>" key="desc" rows="2"><?=htmlspecialchars($banner['desc'])?></textarea></div>
            
            <div>Link</div>
            <div><input class="update" rowid="<?=$banner['id']?>" key="link" value="<?=htmlspecialchars($banner['link'])?>" /></div>
            
            <div class="msg"></div>
        </td>
        
    </tr>
    
    <tr>
    	<td><div style="width:300px;overflow:hidden"><?=str_replace(Won::get('Config')->site_url.'/', '', $banner['imgpath'])?></div></td>
        <td align="right" valign="bottom">   	
			
        	<button class="<?=!$banner['first']? 'moveup' : 'disabled'?>" rowid="<?=$banner['id']?>">▲</button>
            
            <button class="<?=!$banner['last']? 'movedown' : 'disabled'?>" rowid="<?=$banner['id']?>">▼</button>
            
            <button class="remove" rowid="<?=$banner['id']?>">Remove</button>
        </td>
    </tr>      
</table>
<?php } } ?>

	<div id="add-new">
    <form id="new-file-form" action="<?=Won::get('Config')->this_module_url;?>/ajax/upload.php" enctype="multipart/form-data" encoding="multipart/form-data" method="post">
    	<button id="new-btn">Add New</button>               
        <input type="file" id="new-file" name="bannerimage" accept="image/x-png,image/jpeg,image/gif" />        <input type="hidden" name="url" value="<?=Won::get('Permalink')->url;?>" />
        <button id="new-ok">OK</button>    
    </form>
    </div>

<script>
$(function(){
	///////////////////////////////////////////////////////////////////////	
	
	// add	
	$('#new-file, #new-ok').css('display','none');
	
	$('#new-btn').click(function(){
		$(this).css('display','none');
		$('#new-file').css('display','inline-block');	
		return false;	
	});
	
	$('#new-file').change(function(){
		$('#new-ok').css('display','inline-block');
	});	
	
	$('#new-ok').click(function(){
		$('#msg').html(loading);
		$('#new-file-form').submit();		
		return false;
	});


// Remove ///////////////////////////////////////////////////
	$('.remove').click(function(){
		
		// list : the cloest parent table
		var list = $(this).closest('table');
		var status = list.find('.msg');		
		
		var param = {
			method : 'remove',
			id : $(this).attr('rowid')
		};		
		
		load_ajax('<?=Won::get('Config')->this_module;?>', 'controller', param, status, function(data){
			if (data != "")
				status.html('Could not remove the banner');
				
			else			
				list.stop(true).animate({opacity:0}, 1000, function(){
					window.location.href = window.location.href;
				});
		});			
	});



	////////////////////////////////////////////////////////////
	// update 
	// ajax update when key pressed
	$('.update').keyup(function(e){
		
		var list = $(this).closest('table');
		var status = list.find('.msg');
		var param = {
			'method' : 'update',
			'id' : $(this).attr('rowid'),
			'key' : $(this).attr('key'),
			'value' : $(this).val()
		};
		
		load_ajax('<?=Won::get('Config')->this_module;?>', 'controller', param, status, status);		
	});	
	
	
	///////////////////////////////////////////////////////
	// move up & move down
	$('.moveup').click(function(){
		var list = $(this).closest('table');
		var status = list.find('.msg');
		var param = {
			'method' : 'swap',
			'id1' : $(this).attr('rowid'),
			'id2' : list.prev().attr('rowid')
		};
		
		load_ajax('<?=Won::get('Config')->this_module;?>', 'controller', param, status, 'refresh');				
	});
	$('.movedown').click(function(){
		var list = $(this).closest('table');
		var status = list.find('.msg');
		var param = {
			'method' : 'swap',
			'id1' : $(this).attr('rowid'),
			'id2' : list.next().attr('rowid')
		};
		
		load_ajax('<?=Won::get('Config')->this_module;?>', 'controller', param, status, 'refresh');
	});
});
</script>