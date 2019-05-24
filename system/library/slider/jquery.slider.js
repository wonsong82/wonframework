/*
Program Name : Slider
Pre-requisites : jquery, jquery.loadImages
Install : Div element with ul in it must be passed in.
ex:) $('#banner').slider();
*/
(function($){
	
$.fn.slider=function(){
	
	
	// Error checking	
	if($(this).find('ul').length<1){//Check for ul
		throw "Passed element do not contain ul.";
		return false;
	}
		
	if($.isFunction($.loadImages)!=true){//Check for loadImages function
		throw "jquery.loadImages must be loaded first.";
		return false;
	}	
	
	// Get slider type	
	var o=$(this[0]);//this object which is div
	
	// Parse args for general
	var args=arguments[0]||{};
	args.type		=	args.type	||	"fade";
	args.width		=	args.width		||	o.width();
	args.height		=	args.height	||	o.height();
	args.nav		=	args.nav		||	false;	//Show or hide navs
	args.interval	=	args.interval	||	5;	//Pause interval between slides
	args.init		=	args.init		||	false; //Start the transition initially or have a interval

// Expandable //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	// Parse args for specific types
	
	// Fade Slider
	if(args.type=='fade'){ 
		args.speed=args.speed||2;
	}
	
	// Slide Slider	
	if (args.type=='slide'){
		args.speed=args.speed||1; 
	}
	
	// Mask Slider
	if (args.type=='mask'){
		args.speed=args.speed||2;
		args.gap=args.gap||0.2;//Time gap betwwen two masks
		args.box=args.box||13;//Default num of columns
		args.maskW=Math.ceil(args.width/args.box);
		args.maskH=args.height;
	}
	
	// Mask Slider2
	if (args.type=='mask2'){
		args.speed=args.speed||3;//Default tarnsition time
		args.gap=args.gap||0.05;//Default gap betwwen two masks
		args.box=args.box||13;//Default num of columns
		args.mask1Speed = args.speed*0.3;
		args.mask2Speed = args.speed*0.5;
		args.maskStopSpeed = args.gap*args.box;
		args.maskW = Math.ceil(args.width/args.box);
		args.maskH = args.height;
	}
	
	// Mask Slider3
	if (args.type=='mask3'){
		args.speed=args.speed||2;
		args.gap=args.gap||0.05; //The gap between two masks
		args.box=args.box||13; //Number of boxes
		args.maskSpeed=args.speed-(args.gap*args.box);
		var masks=[];
	}
	
	// Box Slider
	if (args.type=='box'){
		args.speed=args.speed||2;
		args.boxX=args.boxX||10;
		args.boxY=args.boxY||5;
		args.maskW=Math.ceil(args.width/args.boxX);
		args.maskH=Math.ceil(args.height/args.boxY);
	}
	
	
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(args.speed>args.interval){
		throw "Transition speed cannot be larger than transition interval.";
		return false;
	}
	
	// Define properties
	var cur=0;
	var timer=null; //Timer for the transition intervals
	var hover=false;
	var con=$('<div class="slider"/>'); //Container that everything resides in (banner, loading, navs).
	var banner=$('<div/>');
	var loading=$('<div class="loading"/>'); //Style controlled by css
	var navLeft=$('<div class="nav-left"/>'); //Style controlled by css
	var navRight=$('<div class="nav-right"/>'); //Style controlled by css
	var list=[]; //Array of elements to be displayed and slided.
	var imgs=[]; //Separte imgs array for preload.
	
	// Clone elements so we can keep the original
	o.find('li').each(function(){
		var element=$('<div/>').html($(this).html()).css({
			'position':'absolute',
			'top':'0px','left':'0px'
		});
		list.push(element);
	});
	if (list.length==0){
		throw "0 elements found for the slides.";
		return false;
	}
	
	// Get imgs for preload
	o.find('img').each(function(){
		imgs.push($(this).attr('src'));
	});
	o.find('ul').css({'opacity':0});
	
	// Define functions
	function showLoading(){
		loading.addClass('loading');
	}
	function hideLoading(){
		loading.removeClass('loading');
	}
	function showNav(){
		navLeft.css('display','block');
		navRight.css('display','block');
	}
	function hideNav(){
		navLeft.css('display','none');
		navRight.css('display','none');
	}
	function play(){
		clearInterval(timer);
		if(list.length>1){
			timer=setInterval(function(){
				next();
			},args.interval*1000);
		}
	}
	function pause(){
		clearInterval(timer);
	}
	
	// Next function, differs by its transition type
	function next(f){
		f=typeof f !== 'undefined' ? f : true;//Forward is true or false, false=backward
		var c=cur; //Cur index
		var n;
		if (f)
			n=(c>=list.length-1)?0:c+1; // Next Index		
		else
			n=(c==0)?list.length-1:c-1; // Prev Index
		
					
		hideNav(); //Hide nav and show when the transition is done
		
// NEXT ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		// Fade Slider
		if(args.type=='fade'){
			banner.empty();
			var element1,element2;
			element1=c==-1?$('<div/>'):list[c].clone();//if init is true, element1 is empty div, otherwise its the clone of the first one from list.
			element1.css('opacity',1).appendTo(banner);
			element2=list[n].clone();
			element2.css('opacity',0).appendTo(banner);
			element1.stop(true).animate({'opacity':0},args.speed*1000);
			element2.stop(true).animate({'opacity':1},args.speed*1000);
		}
		
		// Slide Slider
		if(args.type=='slide'){
			banner.empty().css({'top':'0px','left':'0px'});
			var element1,element2,e2PosX;
			element1=c==-1?$('<div/>'):list[c].clone();
			element1.css({
				'position':'absolute','top':'0px','left':'0px'
			}).appendTo(banner);
			element2=list[n].clone();
			 e2PosX=f?args.width:-1*args.width;
			element2.css({
				'position':'absolute','top':'0px','left':e2PosX+'px'
			}).appendTo(banner);
			banner.stop(true).animate({'left':-1*e2PosX+'px'},args.speed*1000);
		}
		
		// Mask Slider
		if(args.type=='mask'){
			var d=f?1:-1;
			banner.find('.mask').each(function(){
				var mask=$(this);
				mask.empty();
				var maskX=parseInt(mask.css('left'));
				if(c!=-1){//if not init
					var element1=list[c].clone();
					element1.css({'left':(-1*maskX)+'px'}).appendTo(mask);
				}
				var element2 = list[n].clone();
				element2.css({'left':((-1*maskX)+(d*args.width))+'px'}).appendTo(mask);
				if(mask.hasClass('odd')){
					if(c!=-1)
						element1.stop(true).animate({'left':((-1*maskX)-(d*args.width))+'px'},(args.speed-args.gap)*1000, 'easeOutQuart');
					element2.stop(true).animate({'left':(-1*maskX)+'px'},(args.speed-args.gap)*1000, 'easeOutQuart');					
				}
				if(mask.hasClass('even')){
					if(c!=-1)
						element1.stop(true).animate({'left':((-1*maskX)-(d*args.width))+'px'},args.speed*1000,'easeOutQuart');
					element2.stop(true).animate({'left':(-1*maskX)+'px'},args.speed*1000,'easeOutQuart');
				}				
			});
		}
		
		// Mask Slider2
		if(args.type=='mask2'){
			if(c!=-1){ //if not init
				banner.find('.mask.bg').each(function(){
					var mask=$(this);
					mask.empty();
					var element1=list[c].clone();
					element1.css('left','0px').appendTo(mask);
				});
			}
			var ic=0; var oc=0;
			banner.find('.mask.inner').each(function(){
				var mask=$(this);
				mask.empty().css('opacity',0);
				var maskX = parseInt(mask.css('left'));
				var element2=list[n].clone();
				element2.css('left',-1*maskX+'px').appendTo(mask);
				mask.stop(true).delay(ic*args.gap*1000).animate({'opacity':1}, args.mask1Speed*1000, 'easeOutQuart');
				ic++;
			});
			banner.find('.mask.outer').each(function(){
				var mask=$(this);
				mask.empty().css('opacity',0);
				var maskX = mask.attr('left');
				var element2=list[n].clone();
				element2.css('left',-1*maskX+'px').appendTo(mask);
				mask.css('left', maskX-(args.maskW/2) + 'px');
				mask.stop(true).delay((oc*args.gap*1000)+(args.maskStopSpeed*1000)).animate({'opacity':1,'left':mask.attr('left')+'px'}, args.mask2Speed*1000, 'easeOutQuart');
				oc++;
			});
			
		}
		
		// Mask Slider3
		if(args.type=='mask3'){
			if(c!=-1){ //if not init
				banner.find('.mask.bg').each(function(){
					var mask=$(this);
					mask.empty();									
				});
			}
			var maskX=Math.ceil(args.width/args.box);
			var maskW=Math.ceil(maskX*2);
			for (var i=0;i<masks.length;i++){
				clearTimeout(masks[i].timer);
				masks[i].empty().css('display','none');
				masks[i].element=list[n].clone();
				masks[i].element.css('left',(-1*maskW)+'px').appendTo(masks[i]);
			}
			var counter=0;
			for(i=0;i<masks.length;i++){
				masks[i].timer=setTimeout(function(){
					var x=maskX; // for the 1st one
					if(counter!=0){ // for the next ones
						x=parseInt(masks[counter-1].css('left'))+parseInt(masks[counter-1].css('width'));
					}
					masks[counter].css({
						'display':'block',
						'left':x+'px'
					});
					// speed = speed * (0.5 ~ 1)
					var speed = args.maskSpeed*(counter/(args.box-1)*0.5+0.5)*1000;
					masks[counter].stop(true).animate({
						'left':(counter*maskX)+'px'
					},speed, 'easeOutQuart');
					masks[counter].element.stop(true).animate({
						'left':(-1*(counter*maskX))+'px'
					},speed, 'easeOutQuart');
					counter++;
				}, i*args.gap*1000);
			}
		}
		
		// Box Slider
		if(args.type=='box'){
			if(c!=-1){
				banner.find('.mask.bg').each(function(){//Prev
					var mask=$(this);
					mask.empty();
					var element1=list[c].clone();
					element1.appendTo(mask);
				});
			}
			$('.mask.part').each(function(){
				var maskX=parseInt($(this).css('left'));
				var maskY=parseInt($(this).css('top'));
				var mask=$(this);
				mask.empty();
				var element2 = list[n].clone();
				element2.css({'left':-1*maskX+'px','top':-1*maskY+'px','opacity':0}).appendTo(mask);
				element2.stop(true).animate({'opacity':1},(args.speed*500+Math.random()*(args.speed*500)), 'easeInQuart');
			});
		}
		
		
		
		
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		
		banner.timer=setTimeout(function(){ //Increase the timer when the time is completely done.
			if (f)
				cur=cur>=list.length-1?0:cur+1; // Next Index		
			else
				cur=cur==0?list.length-1:cur-1; // Prev Index
			showNav();
		},args.speed*1000);
	}
	
	// Structures for general
	if (o.css('position')=='static') //If root is static, make it to relative so other elements can be added.
		o.css('position','relative');	
	banner.css({ //Where all the slide banners will resides
		'width':args.width+'px','height':args.height+'px',
		'position':'absolute','left':'0px','top':'0px'
	}).appendTo(con);
	if(args.nav==true){ //Add navs if its enabled
		navLeft.css({ //CSS defines img, width, height, top, left
			'position':'absolute',
			'cursor':'pointer',
			'display':'none'
		}).appendTo(con);
		navRight.css({
			'position':'absolute',
			'cursor':'pointer',
			'display':'none'
		}).appendTo(con);
	}
	loading.css('position','absolute').removeClass('loading').appendTo(con);
	con.css({ //Container where all the elements resides in
		'width':args.width+'px','height':args.height+'px',
		'position':'absolute','top':'0px','left':'0px',
		'overflow':'hidden'
	}).appendTo(o);


// STRUCTURE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Structures for specific types
	
	// Fade Slider
	if(args.type=='fade'){}
	
	// Slide Slider
	if(args.type=='slide'){}
	
	// Mask Slider
	if(args.type=='mask'){
		for(var i=0;i<args.box;i++){
			var mask = $('<div/>').css({
				'width':args.maskW+'px',
				'height':args.maskH+'px',
				'position':'absolute','top':'0px','left':i*args.maskW+'px',
				'overflow':'hidden'
			}).addClass('mask');
			if(i%2==0)
				mask.addClass('odd');
			else
				mask.addClass('even');
			mask.appendTo(banner);
		}		
	}
	
	// Mask Slider2
	if(args.type=='mask2'){
		$('<div class="mask bg"/>').css({ // for Prev
			'width':args.width+'px','height':args.height+'px',
			'position':'absolute','top':'0px','left':'0px',
			'overflow':'hidden'
		}).appendTo(banner);
		for (var i=0;i<args.box;i++){ //Masks
			var mask=$('<div/>').css({
				'width':args.maskW+'px',
				'height':args.maskH+'px',
				'position':'absolute','top':'0px','left':i*args.maskW+'px',
				'overflow':'hidden'
			}).addClass('mask').attr('left', i*args.maskW).appendTo(banner);
			var mask2=mask.clone().appendTo(banner);
			mask.addClass('inner');
			mask2.addClass('outer');			
		}
	}
	
	// Mask Slider3
	if(args.type=='mask3'){
		$('<div class="mask bg" />').css({ // for Prev
			'width':args.width+'px','height':args.height+'px',
			'position':'absolute','top':'0px','left':'0px',
			'overflow':'hidden'
		}).appendTo(banner);
		
		args.maskX=Math.ceil(args.width/args.box);
		args.maskW=Math.ceil(args.maskX*1.5); // Mask is 2/3 wider for overwrap effect
		args.maskH=args.height;
		
		for (var i=0; i<args.box; i++){ // Mask1 (Inner)
			$('<div class="mask part" />').css({
				'width':args.maskW+'px','height':args.maskH+'px',
				'position':'absolute','top':'0px','left':(i*args.maskX)+'px',
				'overflow':'hidden'
			}).appendTo(banner);
		}
		
		banner.find('.mask.part').each(function(){
			var m=$(this);
			m.timer = null;
			masks.push(m);
		});
	}
	
	// Box Slider
	if(args.type=='box'){
		$('<div class="mask bg"/>').css({
			'width':args.width+'px','height':args.height+'px',
			'position':'absolute','top':'0px','left':'0px',
			'overflow':'hidden'
		}).appendTo(banner);
		for (var y=0;y<args.boxY;y++){
			for (var x=0;x<args.boxX;x++){
				var mask=$('<div class="mask part"/>').css({
					'width':args.maskW+'px','height':args.maskH+'px',
					'position':'absolute','top':args.maskH*y+'px','left':args.maskW*x+'px',
					'overflow':'hidden'
				}).appendTo(banner);
			}
		}		
		
	}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// Start the Slides
	showLoading();
	$.loadImages(imgs,function(){
		hideLoading();
		if(args.init==true){
			cur=-1;
			next();
			play();
		}
		else{

// INIT EMELENT /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
			// Set Init Image
			
			// Fade Slider
			if (args.type=='fade'){
				var element1=list[0].clone();
				element1.appendTo(banner);
			}
			
			// Slide Slider
			if (args.type=='slide'){
				var element1=list[0].clone();
				element1.appendTo(banner);
			}
						
			// Mask Slider, Mask 3 Slider
			if (args.type=='mask'||args.type=='mask2'||args.type=='mask3'){
				banner.find('.mask').each(function(){
					var mask = $(this);
					mask.empty();
					var maskX = parseInt(mask.css('left'));
					var element1 = list[0].clone();
					element1.css('left',(-1*maskX)+'px').appendTo(mask);
				});
			}
			
			// Box Slider
			if(args.type=='box'){
				banner.find('.mask.bg').each(function(){
					var ele=list[0].clone();
					ele.appendTo($(this));
				});
			}
			
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			play();
		}
		
		//Interactions
		o.hover(function(){
			hover=true;
			//pause();
		},function(){
			hover=false;
			//play();
		});
		navLeft.click(function(){
			pause();
			next(false);
			play();
		});
		navRight.click(function(){
			pause();
			next();
			play();
		});
	});	
	
	o.destroy=function(){
		pause();
		con.remove();
		o.find('ul').css('opacity',1);
	}
	
	return o;
};
	
})(jQuery);