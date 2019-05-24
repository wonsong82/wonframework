<button class="button" id="<?=$id?>"<?=$disabled?> title="<?=$desc?>" style="<?=$css?>"><?=$text?></button> 
	
<? if($action!=''):?>
<?=$reg->loader->getLib('jquery');?>
<script>
$(function(){
	$("#<?=$id?>").click(function(){
		<? if($redirect!=''):?>
		var redirect=<?=$redirect?>;
		<? endif;?>
		var lt=$(this).closest(".page").find(".load");
		var l=$("<div/>").addClass("loading");
		lt.html(l);
		$.ajax({
			"url":encodeURI("<?=$reg->config->site.'ajax/'.$action[0].'/'.$action[1].'/'?>"),
			"type":"post",
			"async":true,
			"cache":false,
			"data":{
				"params":<?=$actionParams?>
			},
			"success":function(d){
				lt.html("");
				d=$.parseJSON(d);
				if(d.status=="ok"){
					<? if($redirect!=''):?>
					window.location.href=redirect;
					<? endif;?>
					<? if($message):?>
					alert("<?=$message?>");
					<? endif;?>
				}
				else
					alert(d.error);
			}
		});
		return false;
	});
});
</script>
<? endif;?>
<? if($action==''&&$redirect!=''):?>
<?=$reg->loader->getLib('jquery');?>
<script>
$(function(){
	$("#<?=$id?>").click(function(){
		window.location.href=<?=$redirect?>;
		return false;
	});
});
</script>
<? endif;?>