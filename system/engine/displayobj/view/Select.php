<div class="select">

	<? if($text):?>
    <label for="<?=$id?>"><?=$text?></label>
    <? endif;?>
    
    <select id="<?=$id?>" class="select" title="<?=$desc?>" <?=$disabled?> style="<?=$css?>">
    	<?=$options?>
    </select>
    
	<? if($action):?>  
    <?=$reg->loader->getLib('jquery');?>
    <script>
	$(function(){
		$("#<?=$id?>").change(function(){
			<? if($redirect!=''):?>
			var redirect=<?=$redirect?>;
			<? endif;?>
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
					
					if(d.status=="ok"){
						<? if($redirect!=''):?>
						window.location.href=redirect;
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

</div>