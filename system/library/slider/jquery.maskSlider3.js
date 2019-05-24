/*
MaskSlider
Must passed in div with ul in it
ex: $('#banner').maskSlider();
loadImages must be loaded first
Navigation is disabled for this version
*/
(function($){

$.fn.maskSlider3=function(){
	
	//Check if ul exists
	if ($(this).find('ul').length<1) {
		throw "The selected object does not contain ul";
		return false;
	}
	
	//Check for prerequisite scripts
	if ($.isFunction($.loadImages)!=true) {
		throw "loadImages must be preloaded.";
		return false;
	}
	
	//Define Objects
	var o=$(this[0]);//this object which is Div
	var args = arguments[0]||{};
	
	//Parse Argments
	var width		= args.width	|| o.width();//Look for width or get it form CSS
	var height		= args.height	|| o.height();
	var isNav		= args.nav		|| false;
	var interval	= args.interval	|| 5;//Default pause time between transitions
	var speed		= args.speed	|| 2;//Default transition speed
	var gap			= args.gap		|| 0.05; //Time gap between two masks
	var box			= args.box		|| 13;//Default num of boxes (columns)
	var init		= args.initAnimation || false;//Whether you start with the animation first, or with the static element of the first one.
	
	
	isNav = false;
	
	//Check for argument errors
	if (speed>interval) {
		throw "Transition speed cannot be longer than Transition Interval.";
		return false;
	}
	
	//Define Properties
	var curItem	=	0;	//Tracking current item
	var timer	=	null;
	var hover	=	false;
	var	con		=	$('<div class="maskSlider3"/>');	//Container that will have banner, loading, navs in it.
	var banner	=	$('<div/>');	//Container of the banners
	var loading	=	$('<div/>');	//Add class banner-loading to display loading image, the style controlled by CSS
	var navLeft	=	$('<div class="nav-left"/>');	//style controlled by CSS
	var navRight=	$('<div class="nav-right"/>');	//style controlled by CSS
	var list	=	[]; //List of items
	var imgs	=	[]; //Get all the images to preload
	
	var maskSpeed = speed - (gap*box);
	var masks = [];
		
	o.find('li').each(function() {
		var li = $('<div/>').html($(this).html()).css({
			'position':'absolute',
			'top':'0px',
			'left':'0px'
		});
		list.push(li); // clone it so we can keep the original
	});
	if (list.length==0) {
		throw "Empty list, no element is found to make a banner out of";
		return false;
	}
	o.find('img').each(function(){
		imgs.push($(this).attr('src'));
	});
	o.find('ul').css({'opacity':0});
	
	//Define Functions
	function turnLoadingOn(){
		loading.addClass('loading');
	}
	function turnLoadingOff(){
		loading.removeClass('loading');
	}
	function showNavs(){
		navLeft.css({'display':'block'});
		navRight.css({'display':'block'});
	}
	function hideNavs(){
		navLeft.css({'display':'none'});
		navRight.css({'display':'none'});
	}
	function play() {
		clearInterval(timer);
		if (list.length>1) {
			timer=setInterval(function(){
				next();
			},interval*1000);
		}
	}
	function pause() {
		clearInterval(timer); //Stop the timer
	}
	
	function next() {
		
		var c = curItem; // Current Index
		var n = (c>=list.length-1)? 0 : c+1; // Next Index
		hideNavs(); // Hide navs and show when the transition is done	
		
				// Set Contents
		
		if (c!=-1) { // for initAnimation false, set background Image, if set true, leave the background blank
			banner.find('.mask.bg').each(function(){
				var mask = $(this);
				mask.empty();
				var element1 = list[c].clone();
				element1.css({
					'left':'0px'				
				}).appendTo(mask);
			});
		}
		
		// for each part
		var maskX = Math.ceil(width/box);
		var maskWidth = Math.ceil(maskX * 2);
		var i =0;
		
		for (i=0; i<masks.length; i++) {
			clearTimeout(masks[i].timer);
			masks[i].empty().css({
				'display':'none'
			});
			masks[i].element = list[n].clone();
			masks[i].element.css({
				'left':(-1*maskWidth)+'px'
			}).appendTo(masks[i]);
		}
		
		var counter=0;
		for (i=0; i<masks.length; i++) {
			masks[i].timer = setTimeout(function(){
				var x = maskX; // for the 1st one
				if (counter!=0) { // for the next ones
					x = parseInt(masks[counter-1].css('left')) + parseInt(masks[counter-1].css('width'));
				}
				masks[counter].css({
					'display':'block',
					'left':x+'px'
				});
				var speed; // speed * (0.5 ~ 1)
				speed = maskSpeed*(counter/(box-1)*0.5+0.5)*1000;
				
				masks[counter].stop(true).animate({
					'left':(counter*maskX)+'px'
				}, speed, 'easeOutQuart');
				masks[counter].element.stop(true).animate({
					'left':(-1*(counter*maskX))+'px'
				}, speed, 'easeOutQuart');
				counter++;
			}, i*gap*1000);
		}
			
		
		banner.timer = setTimeout(function(){
			curItem = (curItem>=list.length-1) ? 0 : curItem+1;
		}, speed*1000);
	}
	
	
	//Setup Structures
	
	// 1. THe Gallery Object, set it to relative so others can be added in it
	o.css({ 
		'position':'relative'
	});
	
	// 2. Container Setup
	con.css({
		'width':width+'px',
		'height':height+'px',
		'position':'absolute',
		'top':'0px',
		'left':'0px',
		'overflow':'hidden'		
	});
	
	// 3. Add Banner Navs, Loading into Container
	banner.css({ // Add banner first
		'position':'absolute',
		'width':width+'px',
		'height':height+'px',
		'top':'0px',
		'left':'0px'
		
	}).appendTo(con);
	
	if (isNav==true){//Add navs if nav is enabled
		navLeft.css({//CSS defines image, width, height, top, left positions.
			'display':'none',
			'position':'absolute',
			'cursor':'pointer'
		}).appendTo(con);
		navRight.css({
			'display':'none',
			'position':'absolute',
			'cursor':'pointer'
		}).appendTo(con);
	}
	
	loading.css({
		'position':'absolute'
	}).appendTo(con);
	
	// 4. Make Masks
	$('<div class="mask bg" />').css({ // for previous
		'width':width+'px',
		'height':height+'px',
		'position':'absolute',
		'overflow':'hidden',
		'top':'0px',
		'left':'0px'
	}).appendTo(banner);
	var i;
	var maskX = Math.ceil(width/box);
	var maskWidth = Math.ceil(maskX * 1.5); // Mask is 2/3 wider for overwrap effect	
	var maskHeight = height;
	for (i=0; i<box; i++) { // Mask1 (Inner)
		$('<div class="mask part" />').css({
			'width':maskWidth+'px',
			'height':maskHeight+'px',
			'overflow':'hidden',
			'position':'absolute',
			'top':'0px',
			'left':(i*maskX)+'px'
		}).appendTo(banner);
	}
	banner.find('.mask.part').each(function(){
		var m = $(this);
		m.timer = null;
		masks.push(m);
	});	
	
	// 5. Add the container
	o.append(con);
	
		
	// Start the banner
	
	
	//Preload Images, and Start
	turnLoadingOn();
	$.loadImages(imgs, function(){ //Start
		turnLoadingOff();
		if (init==true) {
			curItem = -1;
			next();
			play();
		}
		
		else {
			banner.find('.mask').each(function(){ // Set initial Image
				var mask = $(this);
				mask.empty();
				var maskX = parseInt(mask.css('left'));			
				var element = list[0].clone();
				element.css({
					'left': (-1*maskX)+'px'
				}).appendTo(mask);
			});
			play();
		}
		
		//Setup Interactions
		o.hover(function(){
			hover=true;
			pause();
		},function(){
			hover=false;
			play();
		});
		navLeft.click(function(){
			pause()
			prev();
			play();		
		});
		navRight.click(function(){
			pause();
			next();
			play();
		});
	});
	
	
	
	
	o.destroy = function(){
		pause();		
		con.remove();
		o.find('ul').css({'opacity':1});		
	}
	return o;
};


})(jQuery);