<? foreach($this->admin->getMenus() as $menu):?>
<? $selected=preg_match('#/'.$menu['name'].'#',$this->url->uri)? 'class="selected" ':'';?>
<a <?=$selected?>href="<?=$this->config->admin?><?=$menu['name']?>/"><?=$menu['title']?></a>
<? endforeach;?>
