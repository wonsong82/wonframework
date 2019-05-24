<?php include $this->Config->content_dir.'/header.php';?>    
<div id="main" class="home">

    <div id="banner">
    	<ul>
        	<?php foreach ($this->Banner->get_banners() as $banner) {?>
            <li bannerid="<?=$banner['id']?>">
            	<a href="<?=$banner['link']?>"><img src="<?=$banner['imgpath']?>"/></a>
            </li>
        	<?php } ?>
        </ul>
    </div><!--#banner-con-->
    <script src="<?=$this->Config->content_url?>/scripts/jquery.slideBanner2.min.js"></script>
    <script>
		$(function(){
			$('#banner').slideBanner({
				'interval' : 5,
				'speed' : 1,
				'nav':true
			});
		});
	</script>

    <div id="content">
        
        <ul id="imgmenu">
        <?php foreach ($this->Contents->get_elements_by_uri('home') as $e) {
            $title = $e['title'];
            $link = $e['elements'][2]['value']['href'];
            $img = $e['elements'][0]['value']['img'];
            $text = $e['elements'][1]['value'];
        ?>
            <li class="<?=strtolower(preg_replace('/[^a-zA-Z]/','',$title))?>">
                <div class="img">
                    <a href="<?=$link?>">
                        <img src="<?=$img?>" />
                    </a>
                </div>
                <a href="<?=$link?>">
                <div class="text">
                    <h3 class="din"><?=$title?></h3>
                    <p><?=$text?></p>
                    <p class="moreinfo">> <span>More Info</span></p>
                </div>
                </a>          
            </li>  		      
        <?php } ?>
        
        </ul>

    </div>

</div><!--#main-->    
    
<?php include $this->Config->content_dir.'/footer.php';?>

