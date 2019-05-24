<? foreach($this->admin->getSubmenus() as $menu):?>
<a href="<?=$menu['url']?>"<?=$menu['selected']?' class="selected"':''?>><?=$menu['title']?></a>
<? endforeach;?>
