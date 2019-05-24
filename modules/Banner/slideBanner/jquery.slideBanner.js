$.fn.slideBanner = function() {
	
	// get args
	var o = $(this[0]);	
	var args = arguments[0] || {};	
	
	o.find('*').css({ // set everything margin 0 padding 0 border 0
		'margin':'0px',
		'padding':'0px',
		'border':'none'
	});
			
	// parse args	
	args.nav		= args.nav		|| false;
	args.width		= args.width	|| o.css('width');	
	args.height		= args.height	|| o.css('height');	
	args.interval	= args.interval	|| 5;
	args.speed		= args.speed	|| 1;	
	args.width		= args.width.replace('px','');
	args.height		= args.height.replace('px','');
	args.length		= o.find('li').length;
	
	// properties
	var cur 		= 0;
	var timer 		= null;
	var hover 		= false;		
	var loading 	= $('<div/>');
	var con			= o.find('ul');	
	var main		= $('<div/>');	
	var nav 		= {};
	var i 			= 0;				
	nav.left		= $('<div/>').addClass('wonbanner-nav-left');
	nav.right		= $('<div/>').addClass('wonbanner-nav-right');
	
	
	
		
	// define functions
	
	loading.on = function() {
		this.addClass('wonbanner-loading');	
		return this;
	}
	
	loading.off = function() {
		this.removeClass('wonbanner-loading');
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
	
	var play = function() {		
		clearInterval(timer);		
		timer = setInterval(function() {
			cur = (cur >= args.length-1)? 0 : cur+1;
			con.stop(true).animate({
				'left' : -1 * args.width * cur				
			}, args.speed*1000);				
		}, args.interval * 1000);
	}
	
	var pause = function() {
		clearInterval(timer);
	}
	
	var goto = function(index) {
		pause();
		con.stop(true).animate({
			'left' : -1 * args.width * index
		}, args.speed*1000);
		play();
	}	
	
	
		
	
		
	
	// Set up the structures.	
	
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
}
