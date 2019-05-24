<tr rowid="<?=$id?>">
	<? foreach($cols as $k=>$v):?>
    <td class="<?=$k?>">
		<?=$v?>
    </td>
	<? endforeach;?>
    <? foreach($childs as $c): $c->id.='-'.$id;?>
    <td class="<?=$c->id?>">
    	<? $c->render();?>
    </td>
	<? endforeach;?>
</tr>