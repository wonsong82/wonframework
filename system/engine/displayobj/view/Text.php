<div class="text-box">
	<p><?=$text?></p>
    <p class="desc"><?=$desc?></p>
	<textarea id="<?=$id?>" class="text" rows="10"<?=$disabled?>><?=$value?></textarea>
	<?=$reg->loader->getLib('autogrow');?>
	<script>
	$(function(){
		$("#<?=$id?>").autogrow();
		$("#<?=$id?>").keydown(function(e){
			if(e.keyCode==9){
				var start = $(this).get(0).selectionStart;
				$(this).val($(this).val().substring(0,start)+"\t"+$(this).val().substring($(this).get(0).selectionEnd));
				$(this).get(0).selectionStart = $(this).get(0).selectionEnd = start + 1;
				return false;
			}
		});
	});
	</script>
</div>
