<?php
$catname = $this->Permalink->params['cat'];
$items = $this->Product->items_by_title($catname);

$items_html = '';
if (count($items)==0) {
	$items_html = 'No item found';
}
elseif (count($items)==1) {
	header('location:'.$this->Config->site_url.'/product/'.$items[0]['url_friendly_title']);
}

$cats = $this->Product->categories();
$category = $this->Product->category_by_name($catname);
$images = isset($category['images'])? $this->Images->getImages($category['images']) : array();
?>

<?php include $this->Config->content_dir.'/header.php';?>

<div id="main" class="products cat">

    <div id="banner-con">
        <div id="hor-banner">
        	<h1 class="din">PRODUCTS</h1>
            <?php if (count($images)>1){?> 
            	<img src="<?=$images[1]['img']?>" />
            <?php } ?>
        </div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="din"><a href="<?=$this->Config->site_url?>/products">Main</a></li>
        		<?php foreach ($cats as $cat) { ?>
            	<li class="din<?=$catname==$cat['url_friendly_title']? ' current':'';?>"><a href="<?=$this->Config->site_url?>/category/<?=$cat['url_friendly_title']?>"><?=$cat['title']?></a></li>
           		<?php } ?>
            </ul>
        </div>
        
        <div id="page">
        	
             <div id="items">
            	<?=$items_html?>
            </div>
			
        </div>
        
        <div id="side">
        	<div id="side-slogan">
            	<div class="wrap">
        		<h1 class="agaramond">Oravida advocates fresh, natural, nutritional food that's essential for healthier lifestyles.</h1>
                </div>
        	</div>
        </div>        
		
    </div>

</div><!--#main-->  





<?php include $this->Config->content_dir.'/footer.php';?>