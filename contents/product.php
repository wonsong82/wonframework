<?php
$productname = $this->Permalink->params['item'];

// Item
$item = $this->Product->item_by_name($productname);
if (count($item)>0) {
$item['images'] = isset($item['images'])? $this->Images->getImages($item['images']):array();
$item['videos'] = isset($item['videos'])? $this->Videos->getVideos($item['videos']):array();
} else {
	$item = false;
}

// Category
$category = isset($item['category_id'])? $this->Product->category($item['category_id']):null;
$category['images'] = isset($category['images'])? $this->Images->getImages($category['images']) : array();

// for menu
$categories = $this->Product->categories();
?>

<?php include $this->Config->content_dir.'/header.php';?>

<div id="main" class="product">

    <div id="banner-con">
        <div id="hor-banner">
        	<h1 class="din">PRODUCTS</h1>
            <?php if (count($category['images'])>1){?>
            	<img src="<?=$category['images'][1]['img']?>" />
            <?php }?>
        </div>
    </div><!--#banner-con-->


    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="din"><a href="<?=$this->Config->site_url?>/products">Main</a></li>
        		<?php foreach ($categories as $cat) { ?>
            	<li class="din<?=@$category['url_friendly_title']==$cat['url_friendly_title']? ' current':'';?>"><a href="<?=$this->Config->site_url?>/category/<?=$cat['url_friendly_title']?>"><?=$cat['title']?></a></li>
           		<?php } ?>
            </ul>
        </div>
        
        <div id="page">
        <?php if ($item) {?>
        	<div class="item">
        		<h1 class="din"><?=strtoupper($item['title'])?></h1>
            	<p><?=$item['description']?></p>
                <div class="detail">
                <?php if ($item['available']){?>
                	<img src="<?=$item['images'][0]['img']?>" width="258"/>
                    <div class="info">
                    	<div class="p">&yen;<?=$item['price']?></div>
                        <div class="q">Qty. &nbsp;<input type="text" /></div>
                        <div class="w">Unit of Measure: <span><?=$item['weight']?></span></div>
                        <a href="javascript:alert('coming soon');">
                        	<img src="<?=$this->Config->content_url?>/img/add-to-cart-button.jpg" />
                        </a>
                    </div><!--.info-->
                    <div class="more-img-button">
                    	<button class="din">Larger Image and Additional Views</button>              
                    </div>
                    <?php } else { ?>
                    <div class="coming-soon-text din">
                    	COMING SOON
                    </div>
                    <?php } ?>
                </div><!--.detail-->            
			</div><!--.item-->
             <?php } else {echo 'No product found.';} ?>           
        </div>
        
        <div id="side">
        <?php if ($item) {?>
        	<div class="videos">
            	<h1>Watch <?=$item['title']?> Videos</h1>
                <div class="video-con">
            	
				<?php foreach($item['videos'] as $video) {?>
                    <div class="video-item">
                    	<div class="video">
                        	<a href="<?=$video['video']?>">
                            	<img src="<?=$video['thumb']?>"/>
                        	</a>
                        </div>
                        <div class="title">
                        	<h3><?=$video['title']?></h3>
                        </div>
                    </div>
                <?php } ?>
                
                </div>          
                      
            </div><!--.videos-->
            <?php } ?>
        </div>        
	
        <div class="images">
            <ul style="display:none">
                <?php foreach($item['images'] as $img) {?>
                <li thumb="<?=$img['thumb']?>">
                    <img width="<?=$img['original_w']?>" height="<?=$img['original_h']?>" src="<?=$img['original']?>"/>
                </li>
                <?php } ?>
            </ul>
        <script src="<?=$this->Config->content_url?>/scripts/jquery.popupGallery.js"></script>
		<script>
            var pg = $('.images').popupGallery({
				'width':'958px'
			});
            $('.more-img-button button').click(function(){
				pg.show();
			});		
        </script>
        </div><!-- .images-->
        
        
		<div id="videoCon" style="display:none">
            <div id="video" style="width:480px;height:360px">
            </div>
        </div>

        
    </div><!-- #content-->

</div><!--#main-->  

<script>
fv = $("#video").flareVideo({
	'autoplay':true,
	'flashSrc':'<?=$this->Config->site_url?>/lib/flarevideo/media/FlareVideo.swf'
});
fv.onended(function(){
	$('#videoCon').stop(true).animate({'opacity':0},500,function(){
		$('#videoCon').css({'display':'none'});
		$('.videobg').remove();
	});
});
$('.videos .video-item a').click(function(){
	var o=$('#videoCon').parent();
	var v=$(this).attr('href');
	var bg=$('<div/>').css({'position':'fixed','top':'0px','left':'0px','width':'100%','height':'100%','opacity':0,'background':'#000000','z-index':9998}).addClass('videobg').appendTo('html');
	bg.stop(true).animate({'opacity':.5},300);
	var video=$('#videoCon').css({'opacity':0,'display':'block','position':'fixed','width':'480px','height':'360px','top':o.offset().top+50+'px','left':o.offset().left+279+'px','z-index':9999});
	$(window).resize(function(){
		video.css({
			'top':o.offset().top+50+'px',
			'left':o.offset().left+279+'px'
		});
	});
	
	video.stop(true).delay(300).animate({'opacity':1},500,function(){
		fv.load([{
		  src:  v,
		  type: 'video/mp4'	  
		}]);
	});
	
	var xbtn=$('<div class="video-xbtn"></div>').css({'cursor':'pointer'});
	xbtn.click(function(){
		$('#videoCon').stop(true).animate({'opacity':0},500,function(){
			$('#videoCon').css({'display':'none'});
			$('.videobg').remove();
		});
	});
	bg.click(function(){
		$('#videoCon').stop(true).animate({'opacity':0},500,function(){
			$('#videoCon').css({'display':'none'});
			$('.videobg').remove();
		});
	});
	video.append(xbtn);
	
	
	return false;
});
</script>



<?php include $this->Config->content_dir.'/footer.php';?>