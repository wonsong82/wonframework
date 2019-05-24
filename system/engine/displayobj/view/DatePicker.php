<div class="datepicker" id="<?=$id?>-wrap">
	
    <? if($text):?>
	<label for="<?=$id?>"><?=$text?></label>
    <? endif;?>
    
    <input type="text" id="<?=$id?>" class="datepicker" value="<?=$value?>" <?=$disabled?> />
    
</div>

<? if($action):?>
<?=$reg->loader->getLib('jqueryui');?>
<script>
$(function(){
	$('#<?=$id?>').datepicker({
		'dateFormat': 'yy-mm-dd'
	});
	$('#<?=$id?>').change(function(){
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
#<?=$id?> {
	text-align:center;
	width:100px;
}
<?=$css?>
</style>