<?php
$p = $this->Contents->get_elements_by_uri($this->Permalink->uri);
$p = isset($p[0])? $p[0]['value'] : '';

$cats = array();
foreach ($this->Product->categories() as $cat) {
	$imgs = $this->Images->getImages($cat['images']);
	$cat['images'] = $imgs;
	$cats[] = $cat;
}

?>
<?php include $this->Config->content_dir.'/header.php';?>

<div id="main" class="products">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">PRODUCTS</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="current din"><a href="<?=$this->Config->site_url?>/products">Main</a></li>
        		<?php foreach ($cats as $cat) { ?>
            	<li class="din"><a href="<?=$this->Config->site_url?>/category/<?=$cat['url_friendly_title']?>"><?=$cat['title']?></a></li>
           		<?php } ?>
            </ul>
        </div>
        
        <div id="page">
        	
            <div id="product-welcome">
        		<p><?=$p;?></p>
            </div>
			
            <div id="categories">
            	<ul>
					<?php foreach ($cats as $cat) {?>
                	<li>
                    	
                        <h2 class="din"><?=strtoupper($cat['title'])?>
						<?php if (!$cat['available']) {?>
                        <span class="unavailable">(Coming Soon)</span>
                        <?php } ?>
                        </h2>
                        
                        <a href="<?=$this->Config->site_url.'/category/'.$cat['url_friendly_title']?>">                        
                        	<img src="<?=isset($cat['images'][0])? $cat['images'][0]['img']:'';?>"/>
                        </a>
                        
                        	<div class="buy-detail-button">
                            <a href="<?=$this->Config->site_url.'/category/'.$cat['url_friendly_title']?>"><img src="<?=$this->Config->content_url?>/img/buy-detail-button.jpg"/></a> 
                            </div>
                                               
                                            
                    </li>
                	<?php } ?>
                </ul>
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