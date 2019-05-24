$(function() {
/* banner */
	var next = 1;
	var prev = 0;
	var timer = null;
	var li = [];
	var texts = [];
	var banners = [];	
	var interval = 7;
	var transaction_speed = 1;
	var hover = false;
	
	$('#banner').css('position','relative');
	$('#banner li').css({'display':'block', 'position':'absolute','top':'0px','left':'0px','z-index':0});
	$('#banner li h1').css({'opacity':0});		 
	$('#banner li .banner').css({'opacity':0});
	
	var loading = $('<div class="loading"></div>');
	loading.css({'top':'50%', 'left':'50%','position':'absolute','margin-left':'-16px','margin-top':'-16px'});
	$('#banner').append(loading);
	
		
	// insert nav
	
	
	var nav_left = $('<div class="nav-left"></div>');
	var nav_right = $('<div class="nav-right"></div>');
	nav_left.css({
		'padding':'0px',
		'position':'absolute',
		'z-index':5,
		'cursor':'pointer',
		'display':'block'	
	});
	nav_right.css({
		'padding':'0px',
		'position':'absolute',
		'z-index':5,
		'cursor':'pointer',
		'display':'block'
	});	
	$('#banner').append(nav_left);
	$('#banner').append(nav_right);
	
	var nav_click = $('<a class="learn-more" href="#"></a>');
	nav_click.css({
		'padding':'0px',
		'position':'absolute',
		'z-index':100,
		'cursor':'pointer',
		'display':'none'
	});
	$('#banner').append(nav_click);
	
	
	var imgs = [];
	$('#banner img').each(function(){
		imgs.push($(this).attr('src'));
	});	
	
	
	// preload images
	$.loadImages(imgs, function(){
		// animation start
		loading.remove();	
		
		$('#banner li').each(function(){
			li.push($(this));
			texts.push($(this).find('h1').first().css({'z-index':999}));
			banners.push($(this).find('.banner').first());				
		});		
				
		// animate first
		texts[0].css({'z-index':999});
		texts[0].stop(true).delay(700).animate({'opacity':1}, transaction_speed*1000);
		banners[0].stop(true).animate({'opacity':1}, transaction_speed*1000, function(){
			//nav_left.css({'display':'block','opacity':0});
			//nav_right.css({'display':'block','opacity':0});
			//nav_click.css({'display':'block','opacity':0});
			
			var href = banners[0].find('a').attr('href');
			if (href) {
				nav_click.css({'display':'block'});
				nav_click.attr('href', href);
			} else {
				nav_click.css({'display':'none'});
				nav_click.attr('href', '');
			}
			
		});
		banners[0].parent().css('z-index',1);
		
		if (!hover) start_banner_loop();		
	});
	
	// start banner loop
	function start_banner_loop(){		
		clearInterval(timer);	
		
		timer = setInterval(function(){
		texts[prev].stop(true).delay(700).animate({'opacity':0}, transaction_speed*1000);
		texts[next].stop(true).delay(700).animate({'opacity':1}, transaction_speed*1000);
		banners[prev].stop(true).animate({'opacity':0}, transaction_speed*1000);
		banners[prev].parent().css('z-index',0);
		banners[next].stop(true).animate({'opacity':1}, transaction_speed*1000);
		banners[next].parent().css('z-index',1);
		
		var href = banners[next].find('a').attr('href');
		if (href) {			
			nav_click.css({'display':'block'});
			nav_click.attr('href', href);
		} else {
			nav_click.css({'display':'none'});
			nav_click.attr('href', '');
		}
			
		next = (next==li.length-1)? 0 : next+1;		
		prev = (prev==li.length-1)? 0 : prev+1;			
		}
		,
		interval*1000);
	}
	
		

	//nav hover	
	
	$('#banner').hover(
		function(){
			hover = true;
			nav_left.stop(true).animate({'opacity':1},500);
			//nav_right.stop(true).animate({'opacity':1},500);		
			//nav_click.stop(true).animate({'opacity':1},500);				
			//pause the anim
			clearInterval(timer);			
		},
		function(){	
			hover = false;
			//nav_right.stop(true).animate({'opacity':0},500);
			//nav_left.stop(true).animate({'opacity':0},500);
			//nav_click.stop(true).animate({'opacity':0},500);
			// resume the anim
			start_banner_loop();
		}
	);
	
		
	
	nav_left.click(function(){
		clearInterval(timer);
				
		texts[prev].stop(true).animate({'opacity':0}, transaction_speed*1000);
		banners[prev].stop(true).delay(0).animate({'opacity':0}, transaction_speed*1000);
		banners[prev].parent().css('z-index',0);
		
		var prev_back = prev-1;
		if (prev_back==-1) prev_back = li.length-1;
				
		 texts[prev_back].stop(true).animate({'opacity':1}, transaction_speed*1000);
		 banners[prev_back].stop(true).delay(0).animate({'opacity':1}, transaction_speed*1000);
		banners[prev_back].parent().css('z-index',1);
		
		prev = prev_back;
		next = prev_back+1;
		if (prev==li.length) prev = 0;
		if (next==li.length) next = 0;
		
		if (!hover) start_banner_loop();	
	});
	
	nav_right.click(function(){
		clearInterval(timer);
				
		texts[prev].stop(true).animate({'opacity':0}, transaction_speed*1000);
		banners[prev].stop(true).delay(0).animate({'opacity':0}, transaction_speed*1000);
		banners[prev].parent().css('z-index',0);
		
		var nextnext = prev+1;
		if (nextnext == li.length) nextnext = 0;
						
		 texts[nextnext].stop(true).animate({'opacity':1}, transaction_speed*1000);
		 banners[nextnext].stop(true).delay(0).animate({'opacity':1}, transaction_speed*1000);
		banners[nextnext].parent().css('z-index',1);
		
		prev = nextnext;
		next = prev+1;
		if (prev==li.length) prev = 0;
		if (next==li.length) next = 0;
		if (!hover) start_banner_loop();	
	});
});