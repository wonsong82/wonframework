/*



*/
(function($){

$.fn.fadeGallery=function(){
	
	//Error checking
	$(this).each(function(){
		if(!$(this).is('a')){
			throw "Passed collections must be <a/> element.";
			return false;
		}
		if($(this).find('img').length==0){
			throw "Passed a elements must contain <img/>(thumbnail) element.";
			return false;
		}
	});
	
		// args
	var args=arguments[0]||{};
	args.target=args.target||false;
	args.thumbSize=parseInt(args.thumbSize)||50;
	args.borderColor=args.borderColor||'#830113';
		
	if (!args.target){
		throw "Target div must be defined.";
		return false;
	}
	
	if($.isFunction($.loadImages)!=true){//Check for loadImages function
		throw "Jquery.loadImages must be loaded first.";
		return false;
	}
	
	
	//Define Properties
	var i=0;
	var target=$('#'+args.target);
	var frame=$('<div/>').css('position','relative');
	var frameImg=$('<img/>').css('display','block').appendTo(frame);
	var thumbs=$(this);	
	var thumbSelectFrame=$('<div class="selected"/>').css({
		'position':'absolute','top':'0px','left':'0px',
		'width':args.thumbSize-8+'px',
		'height':args.thumbSize-8+'px',
		'border':'4px solid '+args.borderColor
	});
	var loading=$('<div class="loading"/>').css({
		'position':'absolute'
	}); // Controlled by CSS
	var shade=$('<div/>').css({
		'position':'absolute',
		'top':'0px',
		'left':'0px',
		'background':'#000',
		'opacity':0.6
	});
	var holder=$('<div class="holder"/>').css({
		'float':'left',
		'margin-bottom':'2px',
		'margin-right':'2px',
		'width':args.thumbSize+'px',
		'height':args.thumbSize+'px',
		'display':'block',
		'background':'#141414'
	});
	
	shade.timer=null;
	
	// THumb Setup
	thumbs.each(function(i,e){
		$(this).css({
			'opacity':0.5,
			'position':'relative',
			'width':args.thumbSize+'px',
			'height':args.thumbSize+'px',
			'float':'left',
			'margin-bottom':'2px',
			'margin-right':'2px'
		});
		$(this).find('img').css({
			'width':args.thumbSize+'px',
			'height':args.thumbSize+'px',
			'border':'none'
		});
	});
	
	// Thumb empty space holder
	var numThumbs = Math.floor(thumbs.parent().width()/(args.thumbSize+2));
	var moreNumThumbs=numThumbs-(thumbs.length%numThumbs);
	if(moreNumThumbs>0){
		for(i=0;i<moreNumThumbs;i++)
			thumbs.parent().append(holder.clone());
	}
	
	thumbs.parent().append('<div style="clear:both"/>');
		
	
	//Start
	target.append(frame);
	frameImg.appendTo(frame);	
	
	
	function loadImg(element){
		if(!element.hasClass('selected')){
			
			var src=element.attr('href');
			clearTimeout(shade.timer);			
			shade.css({
				'width':frameImg.width()+'px',
				'height':frameImg.height()+'px',
				'opacity':0
			}).appendTo(frame);			
			shade.stop(true).animate({
				'opacity':0.7
			},300);
			shade.timer=setTimeout(function(){
				$.loadImages([src], function(){
					loading.remove();
					frameImg.attr('src',src);
					shade.stop(true).animate({
						'opacity':0
					},300, function(){shade.remove()});
				});
			},300);
			frame.append(loading);
			
			thumbs.removeClass('selected').css('opacity',0.5);
			element.addClass('selected').css('opacity',1).prepend(thumbSelectFrame);		
		}
	}
	
	
	
	//Interactions
	thumbs.hover(function(){
		if($(this).hasClass('selected')==false){
			$(this).stop(true).animate({'opacity':1},300);
		}
	},function(){
		if($(this).hasClass('selected')==false){
			$(this).stop(true).animate({'opacity':0.5},500);
		}
	});
	thumbs.click(function(){
		loadImg($(this));
		return false;
	});
	
	
	//Init
	loadImg(thumbs.first());
};
	
})(jQuery);