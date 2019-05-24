<?php include $this->Config->content_dir.'/header.php';?>

<div id="main" class="news">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">NEWS & EVENTS</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="current">
                	<a class="din" href="<?=$this->Config->site_url?>/news">News & Events</a>
                </li>                
            </ul>
        </div>
        
        <div id="page">
        <ul class="events">
        <?php foreach ($this->Events->getEvents() as $event) { ?>
        	<li>
            	<div class="e-title">
                	<span class="event-date">
                    <?=$event['start_date']==$event['end_date']?
					date('m/d/Y',strtotime($event['start_date'])):
					date('m/d',strtotime($event['start_date'])).' - '.
					date('m/d',strtotime($event['end_date'])).
					date(' Y',strtotime($event['end_date']));?>
                    </span>
                    <span class="event-title"><?=$event['title'];?></span>
                </div>
                <a href="#<?=$event['url_friendly_title']?>" class="read-more">
                <p>Read more</p>
                <img src="<?=$event['images'][0]['thumb']?>" width="100" height="100" />
                </a>
                <div id="<?=$event['url_friendly_title']?>" class="detail">
                	<?php foreach ($event['images'] as $img) {?>
                    <div class="event-content">
                    	<h1 class="din"><?=$event['title']?></h1>
                        <p><?=$event['content']?></p>
                        <div class="img">
                        	<img src="<?=$img['img']?>" />
                        </div>
                    </div>
                    <?php } ?>
                </div><!--.detail-->   
			
            </li>
        <? } ?>
        </ul>
        <script src="<?=$this->Config->content_url?>/scripts/jquery.popup.js"></script>
		<script>
			$('.events>li').each(function(){
				var popup = $(this).find('.event-content').popup();
				$(this).find('.read-more').click(function(){
					popup.show();
					return false;
				});
			});
						
        </script>
        </div><!--#page-->
        
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