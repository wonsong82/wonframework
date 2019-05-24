<div class="checkbox-box" >
	
	<? if($text):?>
    <label for="<?=$id?>"><?=$text?></label>    
	<? endif;?>
	
	<input type="checkbox" id="<?=$id?>" class="checkbox" title="<?=$desc?>" <?=$checked?> value="<?=(int)$value?>" <?=$disabled?> style="<?=$css?>"/>
    
	<script>
	$(function(){
		$("#<?=$id?>").change(function(){
			if($(this).is(":checked"))
				$(this).attr("value","1");
			else
				$(this).attr("value","0");
			
			<? if($action!=''):?>
			<? if($redirect!=''):?>
			var redirect=<?=$redirect?>;
			<? endif;?>
    		var lt=$(this).closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			lt.html(l);
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/'.$action[0].'/'.$action[1].'/';?>"),
				"type":"post",
				"async":"true",
				"data":{
					"params":<?=$actionParams?>
				},
				"cache":false,
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
			<? endif;?>
		});			
	});
	</script>
    
</div>