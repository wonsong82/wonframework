/*
Just to give a little lighter opacity on mouse hover on the elements

group of elements must be handed in
*/
$.fn.highlight=function(){
	
	//Check for value handed
	if ($(this).length < 1) {
		throw "No Elements passed";
		return false; 
	}
	
	//Define
	var elements=$(this);
	var args=arguments[0]||{};
	
	//Parse Args
	var color=args.color||'#ffffff';
	var opacity=args.opacity||0.5;	
	
	elements.each(function(){
		if ($(this).css('position')=='static') {
			$(this).css('position','relative');
		}
		$(this).children().each(function(){
			if($(this).css('position')=='static')
				$(this).css('position','relative');
		});
		var highlighter=$('<div></div>').css({
			'background':color,
			'opacity':0,
			'width':$(this).width(),
			'height':$(this).height(),
			'position':'absolute',
			'top':0,
			'left':0
		}).prependTo(this);		
		$(this).hover(
			function(){
				highlighter.stop(true).animate({'opacity':opacity}, 100);
			},
			function(){
				highlighter.stop(true).animate({'opacity':0}, 400);		
			}
		);
	});
	
}