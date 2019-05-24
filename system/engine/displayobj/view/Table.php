<table id="<?=$id?>" class="table">
	<? foreach($rows as $rowObj):?>
	<? $rowObj->render();?>   
    <? endforeach;?>
</table>
<?=$reg->loader->getLib('jqueryui');?>
<script>
$(function(){
	$("#<?=$id?> tr").each(function(){
		var w=0,cn=0,colw=0;
		$("td",this).each(function(){
			if($(">button",this).length>0)
				w+=$(">button",this).width();
			else
				cn++;			
		});
		colw=Math.floor(($("#<?=$id?>").width()-w)/cn);
		$("td",this).each(function(){
			if($(">button",this).length>0)
				$(this).width($(">button",this).width());
			else
				$(this).width(colw);
		});
	});
	<? 
	// If Sortable is Enabled
	if($order!=''):
		$param=explode(',',$order);
		$module=$param[0];
		$table=$param[1];
	?>
	$("#<?=$id?> tbody").sortable({
		"update":function(e,ui){
			var ids=[];
			$(">tr",e.target).each(function(){
				ids.push($(this).attr("rowid"));
			});
			ids=ids.join(",");
			var lt=$(this).closest(".page").find(".load");
			var l=$("<div></div>").addClass("loading");
			lt.html(l);
			$.ajax({
				"url":encodeURI("<?=$reg->config->site.'ajax/'.$module.'/updateOrder/'?>"),
				"type":"post",
				"async":true,
				"data":{
					"params":["<?=$table?>",ids]
				},
				"cache":false,
				"success":function(d){
					lt.html("");
					d=$.parseJSON(d);
					if(d.status=="ok");
					else
						alert(d.error);
				}
			});
		}
	});	
	<? endif;?>	
});
</script>