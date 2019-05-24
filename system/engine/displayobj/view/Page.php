<?php
$args='';
foreach($reg->url->args as $k=>$v)
	$args.=' '.$k.'="'.$v.'"';
?>
<h1><?=$text?></h1>
<div id="<?=$id?>" class="page"<?=$args?>>
	<div class="load"></div>
	<? if($desc):?>
	<p class="desc"><?=$desc?></p>
    <? endif;?>
	<? foreach($childs as $c){
			$c->render();
	}?>
</div>