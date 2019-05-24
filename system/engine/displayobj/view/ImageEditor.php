<div class="image-editor">
	<p>Image Shortcut Key : <?=$text?></p>
    <br/>
    
    <div class="editor">
    	<h3>Image</h3>		
        <img id="img" src="<?=$img['o_src'];?>" width="600"/>   
    </div>
    
    <br/><br/>
    
    <div class="thumb">
    	<h3>Thumbnail</h3>
    	<img id="thumb" src="<?=$img['o_src'];?>" width="300" />
    </div>
</div>
<br/>
<br/>

<?=$reg->loader->getLib('imageEditor');?>
<script>
$(function(){
	 $("#img").imageEditor({
		 'width':<?=$img['o_width']?>,
		 'height':<?=$img['o_height']?>,
		 'values':[<?=$imgData['rw']?>,<?=$imgData['rh']?>,<?=$imgData['sx']?>,<?=$imgData['sy']?>,<?=$img['width']?>,<?=$img['height']?>],
		'update':function(v){
			var lt=$("#img").closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			lt.html(l);
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/Image/updateImage/'?>"),
				"type":"post",
				"async":true,
				"cache":false,
				"data":{
					"params":[<?=$img['id']?>,v.rw,v.rh,v.x,v.y,v.w,v.h]
				},
				"success":function(d){
					lt.html("");
					d=$.parseJSON(d);
					if(d.status=="ok"){
					} else {
						alert(d.error);
					}
				}
			});
		}
	 });
	 
	 $("#thumb").imageEditor({
		 'width':<?=$img['o_width']?>,
		 'height':<?=$img['o_height']?>,
		 'values':[<?=$imgData['t_rw']?>,<?=$imgData['t_rh']?>,<?=$imgData['t_sx']?>,<?=$imgData['t_sy']?>,<?=$img['t_width']?>,<?=$img['t_height']?>],
		'update':function(v){
			var lt=$("#img").closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			lt.html(l);
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/Image/updateThumb/'?>"),
				"type":"post",
				"async":true,
				"cache":false,
				"data":{
					"params":[<?=$img['id']?>,v.rw,v.rh,v.x,v.y,v.w,v.h]
				},
				"success":function(d){
					lt.html("");
					d=$.parseJSON(d);
					if(d.status=="ok"){
					} else {
						alert(d.error);
					}
				}
			});
		}
	 });
});
</script>
