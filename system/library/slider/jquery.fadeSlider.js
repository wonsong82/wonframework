/*
MaskSlider
Must passed in div with ul in it
ex: $('#banner').maskSlider();
loadImages must be loaded first
Navigation is disabled for this version
*/
(function($){

$.fn.fader=function(){
	
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
	var init		= args.initAnimation || false;//Whether you start with the animation first, or with the static element of the first one.
	
	
	//Check for argument errors
	if (speed>interval) {
		throw "Transition speed cannot be longer than Transition Interval.";
		return false;
	}
	
	//Define Properties
	var curItem	=	0;	//Tracking current item
	var timer	=	null;
	var hover	=	false;
	var	con		=	$('<div class="fadeSlider"/>');	//Container that will have banner, loading, navs in it.
	var banner	=	$('<div/>');	//Container of the banners
	var loading	=	$('<div/>');	//Add class loading to display loading image, the style controlled by CSS
	var navLeft	=	$('<div class="nav-left"/>');	//style controlled by CSS
	var navRight=	$('<div class="nav-right"/>');	//style controlled by CSS
	var list	=	[]; //List of items
	var imgs	=	[]; //Get all the images to preload
	
	 // clone lists of elements so we can keep the original		
	o.find('li').each(function() {
		var li = $('<div/>').html($(this).html()).css({
			'position':'absolute',
			'top':'0px',
			'left':'0px'
		});
		list.push(li);
	});
	if (list.length==0) {
		throw "Empty list, no element is found to make a banner out of";
		return false;
	}
	
	// get imgs for preload images
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
	
	// Start auto looping 
	function play() {
		clearInterval(timer);
		if (list.length>1) {
			timer=setInterval(function(){
				next();
			},interval*1000);
		}
	}
	
	// Pause auto looping
	function pause() {
		clearInterval(timer); //Stop the timer
	}
	
	// Next
	function next() {
		
		var c = curItem; // Current Index
		var n = (c>=list.length-1)? 0 : c+1; // Next Index
		hideNavs(); // Hide navs and show when the transition is done	
		
				// Set Contents
		
		banner.empty();
		
		var element1,element2;
		 
		 // for initAnimation false, set background Image, if set true, leave the background blank
		element1=c==-1?$('<div/>'):list[c].clone();
		element1.css({
			'opacity':1
		}).appendTo(banner);
		
		element2=list[n].clone();
		element2.css({
			'opacity':0
		}).appendTo(banner);
				
		element1.stop(true).animate({
			'opacity':0
		}, speed*1000);
		element2.stop(true).animate({
			'opacity':1
		}, speed*1000);
					
		
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
		
		else { // Set initial Image
			var element = list[0].clone();
			element.appendTo(banner);
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