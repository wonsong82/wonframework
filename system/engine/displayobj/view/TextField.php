<div class="textfield-box" id="<?=$id?>-wrap">
	
	<? if($text):?>
    <label for="<?=$id?>"><?=$text?></label>    
	<? endif;?>

	<? if($static):?>
    <span id="<?=$id?>" class="statictext"><?=$value?></span>
    <? else:?>
    <input type="<?=$type?>" id="<?=$id?>" class="textfield" title="<?=$desc?>" value="<?=$value?>" <?=$disabled?>/>
    <? endif;?>
        
    <!--<span class="error" id="<?=$id.'-msg'?>"></span>-->
    
	<? if($action):?>
    <?=$reg->loader->getLib('jquery');?>
    <script>
	$(function(){
		$("#<?=$id?>").keyup(function(){
			var lt=$(this).closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			var msg=$(this).closest(".page").find(".msg");
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
					if(d.status=="ok"){
						msg.html("").removeClass("error");
						$("#<?=$id?>").removeClass("error");
					}
					else{
						msg.html(d.error).addClass("error");
						$("#<?=$id?>").addClass("error");
					}
				}
			});
			return false;
		});
	});
	</script>
    <? endif;?>
    <style>
		<?=$css?>
	</style>
</div>