<button class="button" id="<?=$id?>"><?=$text?></button>
<div id="msg"></div>
<?=$reg->loader->getLib('uploader.html5');?>
<?=$reg->loader->getLib('uploader.iframe');?>
<script>
$(function(){
	<? if($redirect!=''):?>
	var redirect=<?=$redirect?>;
	<? endif;?>
	
	function success(d){	
		d=$.parseJSON(d);
		if(d.status=="ok"){
			var uploadedFile = d.data;
			var lt=$("#<?=$id?>").closest(".page").find(".load");
			var l=$("<div/>").addClass("loading");
			lt.html(l);
			
			<? if($type == 'image'):?>
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/Image/add/'?>"),
				"type":"post",
				"async":true,
				"cache":false,
				"data":{
					"params":[uploadedFile, <?=$imgWidth?>, <?=$imgHeight?>, <?=$thumbWidth?>, <?=$thumbHeight?>]
				},
				"success":function(d){
					lt.html("");
					d=$.parseJSON(d);
					if(d.status=="ok"){
						<? if($action!=''): ?>
						var imageId = d.data;
						alert(d.data);
						var params = <?=$actionParams?>;
						params.unshift(d.data); // add image id to the params
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
								}
								else
									alert(d.error);							
							}
						});
						<? endif;?>
						<? if($action==''&&$redirect!=''):?>
						window.location.href=redirect;
						<? endif;?>
					}
					else
						alert(d.error);
				}
			});
			
			<? else: // File?>
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
					}
					else
						alert(d.error);							
				}
			});
			
			<? endif;?>
		}
		else{
			alert(d.error);
		}
	}
	
	// Html5 Uploader
	if(window.File && window.FileReader && window.FileList && window.Blob){
		$("#<?=$id?>").html5uploader({
			"action":encodeURI("<?=$reg->config->site.'ajax/File/uploadPart/'?>"),
			"accept":"<?=$type?>/*",
			"success":success
		});
	}
	
	// Legacy Uploader
	else{
		$("#<?=$id?>").uploader({
			"action":encodeURI("<?=$reg->config->site.'ajax/File/upload/'?>"),
			"limit":"<?=ini_get('upload_max_filesize');?>",
			"accept":"<?=$type?>/*",
			"success":success
		});
	}
	
	
});


</script>
