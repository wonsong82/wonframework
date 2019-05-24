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
	
    <div class="msg"></div>
	
    
	<? foreach($childs as $c){
			if(gettype($c)=='string')
				echo $c;
			else
				$c->render();
	}?>
</div>