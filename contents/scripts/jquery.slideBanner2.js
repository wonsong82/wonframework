/*
Slide banner with 1-2-3-4 and then 1-2-3-4.
Must passed in div with ul in it
ex : $('#banner').slideBanner();
loadImages just be loaded first
*/
$.fn.slideBanner=function(){	
	
	//Check if this is ul
	if ($(this).find('ul').length<1){
		throw "The object passed in is not ul";
		return false;
	}
	
	//Check for prerequisite scripts
	if ($.isFunction($.loadImages)!==true){
		throw "loadImages must be preloaded.";
		return false;
	}
	
	//Define Objects
	var o=$(this[0]);//this
	var args=arguments[0]||{};
	
	//Parse Args
	var width=args.width||o.width();//Look for width or get it from CSS
	var height=args.height||o.height();
	var isNav=args.nav||false;
	var interval=args.interval||5;//Default pause time between animations
	var speed=args.speed||1;//Transition speed
	
	//Check for argument errors
	if (speed>interval){
		throw "Transition speed cannot be longer than Transition Interval";
		return false;
	}
	
	
	//Define Properties		
	var curItem=0;//Tracking current item
	
	var timer=null;
	var hover=false;
	var con=$('<div/>');//Container that will have banner, loading, navs in it.
	var banner=$('<div/>');
	var loading=$('<div/>');//Add class banner-loading to display loading image, the style controlled by CSS
	var navLeft=$('<div class="nav-left"/>');//style controlled by CSS
	var navRight=$('<div class="nav-right"/>');//style controlled by CSS
	
	//Get lists of items
	var list=[];//Each banner content lists
	o.find('li').each(function(){
		list.push($(this));
	});
	if (list.length==0){
		throw "Empty list, no element is found to make a banner out of";
		return false;
	}
	
	// Get all the images
	var imgs=[];//to Preload
	o.find('img').each(function(){
		imgs.push($(this).attr('src'));
	});
	
	
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
	function play(){
		clearInterval(timer);
		timer=setInterval(function(){
			next();
		},interval*1000);		
	}
	function pause(){
		clearInterval(timer);//stop the timer
	}
	function next(){
		var c=curItem;//Current Index
		var n=(c>=list.length-1)?0:c+1;//Next Index
		hideNavs();//Hide navs and show when the transition is done
		banner.empty().css({'top':'0px','left':'0px'});//empty out the banner and position it
		list[c].css({'position':'absolute','top':'0px','left':'0px'}).appendTo(banner);//setup the first item
		list[n].css({'position':'absolute','top':'0px','left':width+'px'}).appendTo(banner);//setup the next item
		banner.stop(true).animate({'left':-1*width+'px'},speed*1000,function(){
			curItem=(curItem>=list.length-1)?0:curItem+1;
			showNavs();
		});
	}
	function prev(){
		var c=curItem;//Current Index
		var p=(c==0)?list.length-1:c-1;//Next Index
		hideNavs();
		banner.empty().css({'top':'0px','left':-1*width+'px'});
		list[p].css({'position':'absolute','top':'0px','left':'0px'}).appendTo(banner);
		list[c].css({'position':'absolute','top':'0px','left':width+'px'}).appendTo(banner);
		banner.stop(true).animate({'left':'0px'},speed*1000,function(){
			curItem=(c==0)?list.length-1:c-1;
			showNavs();
		})
	}
	
	
	
	//Setup Structures
	
	// 1. Banner container
	con.width(width);
	con.height(height);
	con.css({
		'overflow':'hidden',//To make it not affecting  other element style.
		'position':'relative'//To append childrens in it.
	});
	
	// 2. Add banner, navs, loading into container
	banner.css({//Add banner first
		'position':'absolute'
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
		
	// 3. Clear the object and add the container
	o.empty();
	o.append(con);
	
	
	
	//Start the banner
	
	//Preload Images, and Start
	turnLoadingOn();
	$.loadImages(imgs,function(){//Start
		turnLoadingOff();
		banner.append(list[0]);//Add first item
		showNavs();
		
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
		
		//Play
		play();
	});
	
	
	
	return o;	
}