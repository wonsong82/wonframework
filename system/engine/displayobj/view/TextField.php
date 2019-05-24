<div class="textfield-box">
	
	<? if($text):?>
    <label for="<?=$id?>"><?=$text?></label>    
	<? endif;?>

	<? if($static):?>
    <span id="<?=$id?>" class="statictext" style="<?=$css?>"><?=$value?></span>
    <? else:?>
    <input type="<?=$type?>" id="<?=$id?>" class="textfield" title="<?=$desc?>" value="<?=$value?>" <?=$disabled?> style="<?=$css?>"/>
    <? endif;?>
        
    <span class="error" id="<?=$id.'-msg'?>"></span>
    
	<? if($action):?>
    <?=$reg->loader->getLib('jquery');?>
    <script>
	$(function(){
		$("#<?=$id?>").keyup(function(){
			var lt=$(this).closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			lt.html(l);
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/'.$action[0].'/'.$action[1].'/'?>"),
				"type":"post",
				"cache":false,
				"async":true,
				"data":{
					"params":<?=$actionParams?>
				},
				"success":function(d){
					lt.html("");
					d=$.parseJSON(d);
					if(d.status=="ok")
						$("#<?=$id.'-msg'?>").html("");
					else
						$("#<?=$id.'-msg'?>").html(d.error);
				}
			});
			return false;
		});
	});
	</script>
    <? endif;?>
</div>