$.fn.popupGallery = function() {
	
	// get args
	var o = $(this[0]);	
	var args = arguments[0] || {};	
	
	o.find('*').css({ // set everything margin 0 padding 0 border 0
		'margin':'0px',
		'padding':'0px',
		'border':'none'
	});
			
	// parse args	
	args.width		= args.width	|| o.css('width');	
	args.imgwidth	= args.imgwidth || '521';
	args.thumbwidth = args.thumbwidth || '75';
	args.thumbheight= args.thumbheight|| '75';
	
	args.width		= parseInt(args.width.replace('px',''));
	args.imgwidth	= parseInt(args.imgwidth.replace('px',''));
	args.thumbwidth = parseInt(args.thumbwidth.replace('px',''));
	args.thumbheight= parseInt(args.thumbheight.replace('px',''));
	
	args.length		= o.find('li').length;
	
	// properties
	var loading 	= $('<div/>');
		
	var main		= $('<div/>');
	main.bg			= $('<div/>');	
	
	var imgCon		= $('<div/>');
	var img			= $('<img/>');
	var thumbCon   = $('<div/>');
			
	var nav 		= {};
	nav.left		= $('<div/>').addClass('popupgallery-nav-left');
	nav.right		= $('<div/>').addClass('popupgallery-nav-right');
	nav.x			= $('<div/>').addClass('popupgallery-nav-x');
		
	var li			= o.find('li');
	var imgs=[];
	li.each(function(){
		var img={};
		img.thumb = $(this).attr('thumb');
		img.img = $(this).find('img').attr('src');
		img.width = parseInt($(this).find('img').attr('width'));
		img.height = parseInt($(this).find('img').attr('height'));
		imgs.push(img);
	});
	
	
	var i 			= 0;				
	
	var cur			= 0;
	
	o.html('');	
	// define functions
	
	loading.on = function() {
		this.addClass('popupgallery-loading');	
		return this;
	}
	
	loading.off = function() {
		this.removeClass('popupgallery-loading');
		return this;
	}
	
	nav.show = function() {
		this.left.css('display' , 'block');
		this.right.css('display' , 'block');
	}
	
	nav.hide = function() {
		this.left.css('display' , 'none');
		this.right.css('display' , 'none');
	}
	
	main.show = function() {
		
		// bg
		main.bg.css({'opacity':0});
		main.bg.appendTo('html');
		main.bg.stop(true).animate({'opacity':.5}, 300);
		
		// main
		main.css({'opacity':0});
		imgCon.css({'opacity':0});
		
		main.appendTo('html');
		main.stop(true).delay(200).animate({'opacity':1},300, function(){
			
			img.click(function(){
				cur++;
				if (cur>=imgs.length)
					cur=0;
				main.play(cur);
			});
			nav.right.click(function(){
				cur++;
				if (cur>=imgs.length) cur=0;
				main.play(cur);
			});
			nav.left.click(function(){
				cur--;
				if (cur<0) cur=imgs.length-1;
				main.play(cur);
			});
			nav.x.click(function(){
				main.hide();
			});
			main.bg.click(function(){
				main.hide();
			});
			
			
			main.play(cur);
			
			
		});
		
	}
	
	main.hide = function() {
		cur=0;
		main.remove();
		main.bg.remove();		
	}
	
	main.play = function(i) {
		i = parseInt(i);
		var w = (imgs[i].width > args.imgwidth)? args.imgwidth : imgs[i].width;
		var h = (imgs[i].width > args.imgwidth)? 
			(args.imgwidth * imgs[i].height)/imgs[i].width : imgs[i].height;		
				
		imgCon.css({
			'width':w+'px',
			'height':h+'px',
			'opacity':0
		});		
		
		thumbCon.empty();
		var pt = (i-1)<0? imgs.length-1:i-1;
		var ct = i;
		var nt = (i+1)>=imgs.length? 0:i+1;
		var ts = [pt,ct,nt];
				
		for (y=0; y<ts.length; y++) {
			var t = $('<div/>');
			t.attr('index',ts[y]);
			t.addClass('popupgallery-thumb');
			t.css({
				'cursor':'pointer',
				'width':args.thumbwidth+'px',
				'height':args.thumbheight+'px'
			});
			if (ts[y]==cur)
			t.addClass('popupgallery-current-thumb');
			var timg = $('<img/>');
			timg.css({
				'width':args.thumbwidth+'px',
				'height':args.thumbheight+'px'
			});
			timg.attr('src',imgs[ts[y]].thumb);
			timg.appendTo(t);
			t.click(function(){
				cur=$(this).attr('index');
				main.play(cur);
			});
			
			t.appendTo(thumbCon);
			
		}
			
		
		
		loading.on();
		$.loadImages(imgs[i].img, function(){
			loading.off();
			
			img.css({
				'width':w+'px',
				'height':h+'px'
			});
			img.attr('src',imgs[i].img);
			
			imgCon.stop(true).animate({'opacity':1},500);					
		});		
	}
	
			
	
		
	
	// Set up the structures.	
	
	main.css({
		'position'	: 'fixed',
		'width'		: args.width + 'px',
		'top'		: o.offset().top + 'px',
		'left'		: o.offset().left +'px',
		'background': '#ffffff',
		'z-index'	: 9999
	}).addClass('popupgallery');
	
	main.bg.css({
		'position':'fixed',
		'top':'0px',
		'left':'0px',
		'width':'100%',
		'height':'100%',
		'opacity':.5,
		'background':'#000000',
		'z-index'	: 9998	
	});
	
	imgCon.css({
		'margin':'0px auto',
		'padding':'50px 0px 10px'
	});
	imgCon.appendTo(main);
	
	img.css({
		'border':'0',
		'cursor':'pointer'
	});	
	img.appendTo(imgCon);	
	loading.appendTo(main);	
	nav.left.css({'cursor':'pointer'});
	nav.left.appendTo(main);
	nav.right.css({'cursor':'pointer'});
	nav.right.appendTo(main);
	nav.x.css({'cursor':'pointer'});
	nav.x.appendTo(main);
	thumbCon.appendTo(main);
	thumbCon.addClass('popupgallery-thumbcon');
	
	
	
	
	// start
	
	
	$(window).resize(function(){
		main.css({
			'top' : o.offset().top + 'px',
			'left' : o.offset().left + 'px' 
		});
	});
	
		
	
	return main;
	
	/*
	o.css({
		'position'	: 'relative',
		'width'		: args.width + 'px',
		'height'	: args.height + 'px'
	});
	
	main.css({ // main wraps con, set as a frame of the sliding artworks
		'position'	: 'relative',
		'width'		: args.width + 'px',
		'height'	: args.height + 'px',
		'overflow'	: 'hidden'
	});	
	
	con.css({ // con is container of the sliding artworks
		'display':'none',
		'position':'absolute',
		'top':'0px',
		'left':'0px'
	}); 
	
	o.append(loading); // insert loading
	
	con.wrap(main); 
		
	if (args.nav) { // nav
		nav.left.css({
			'display':'none',
			'position':'absolute',
			'cursor':'pointer'
			
		}).appendTo(o);
		nav.right.css({
			'display':'none',
			'position':'absolute',
			'cursor':'pointer'
		}).appendTo(o);
	}
		
	i=0; // each list, place each list one next to each other
	con.find('li').each(function(){
		$(this).css({		
			'position'	: 'absolute',
			'width'		: args.width + 'px', 
			'height'	: args.height + 'px',
			'overflow'	: 'hidden',
			'left'		: args.width*i + 'px',
			'top'		: '0px'
		});
		i++;		
	});
	
	
	
	
	// start the banner
	
	loading.on(); // turn on loading
		
	var imgs = []; // Preload Images	
	o.find('img').each(function(){
		imgs.push($(this).attr('src'));
	});
	$.loadImages(imgs, function(){
		loading.off();
		nav.show();
		con.css('display','block');
		if (!hover) play();
	});

	
	

	// Set up the events
	
	o.hover( // stops when hovered, resume when not hovered
		function(){
			hover = true;						
			pause();			
		},
		function(){	
			hover = false;			
			play();
		}
	);
	
	nav.left.click(function(){
		cur--;
		if (cur < 0) cur = 0;
		goto(cur);	
	});
	
	nav.right.click(function(){
		cur++;
		if (cur >= args.length-1) cur = args.length-1;
		goto(cur);
	});
	
	
	return o;
	*/
}
