/*
Make a passed DIV a full screen.
width and hieight must be defined.
stretch(bool) must be defined.
*/
(function($){
$.fn.fullScreen=function(){
	
	var args=arguments[0]||{};
	args.width=parseInt(args.width)||false;
	args.height=parseInt(args.height)||false;
	args.stretch=args.stretch||false;
	
	var bg=$(this[0]);
	
	if(!bg.is('div')){
		throw "Passed element must be div";
		return false;
	}
	
	if(!args.width||!args.height){
		throw "Must define width & height";
		return false;
	}
	
	$('html,body').css({
		'margin':'0px','padding':'0px'
	});
	
	bg.css({
		'position':'absolute',
		'top':'0px','left':'0px'
	});
	
	resize();
	$(window).resize(resize);
	
	function resize(){
		var windowW=$(window).width();
		var windowH=$(window).height();
		
		var bgW=windowW>args.width?windowW:args.width;
		var bgH;
		
		
		if(args.stretch)
			bgH=windowH>args.height?windowH:args.height;
		else 
			bgH=(bgW*args.height)/args.width;		
		
		bg.width(bgW);
		bg.height(bgH);
		
		if(!args.stretch){
			bg.css({
				'top':(windowH-bgH)/2+'px'
			});
		}
		
		
	}
}	
})(jQuery);