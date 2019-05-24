$.fn.popup = function() {
	
	// get args
	var o = $(this);	
	var args = arguments[0] || {};	
	
	// parse args	
	args.overlayColor		= args.overlayColor	|| '#000000';
	args.overlayOpacity		= args.overlayOpacity || .5;
	args.width				= args.width || 0;
			
	// properties
	var loading 	= $('<div/>');
		
	var overlay		= $('<div/>');	
	var popup		= $('<div/>');
	var popupContent= $('<div/>');
					
	var nav 		= {};
	nav.left		= $('<div/>').addClass('popup-nav-left');
	nav.right		= $('<div/>').addClass('popup-nav-right');
	nav.x			= $('<div/>').addClass('popup-nav-x');
		
	var contents	= [];
	o.each(function(){		
		var content = $(this);
		content.width($(this).width());
		contents.push(content);
		$(this).remove();
	});
	
	
	var i 			= 0;				
	var cur			= 0;
	
	// define functions
	
	loading.on = function() {
		this.addClass('popup-loading');	
		return this;
	}
	
	loading.off = function() {
		this.removeClass('popup-loading');
		return this;
	}
	
	nav.show = function() {
		this.left.css('display' , 'block');
		this.right.css('display' , 'block');
		this.x.css('display','block');
	}
	
	nav.hide = function() {
		this.left.css('display' , 'none');
		this.right.css('display' , 'none');
		this.x.css('display','none');
	}
	
	popup.show = function() {
		$('body').css('position','relative');
		
		// bg
		var pageScroll = getPageScroll();
		
		// Set up the structures.	
		var pageSize = getPageSize();	
		var pageScroll = getPageScroll();
		var width = args.width!=0? args.width : contents[cur].width();
		popup.css({
			'position'	: 'absolute',
			'width'		: width+'px',
			'top'		: pageScroll[1]+(pageSize[3]/10)+'px',
			'left'		: pageScroll[0]+((pageSize[0]-width)/2)+'px',
			'background': '#ffffff'		
		}).addClass('popup');
		
		overlay.css({
			'position':'fixed',
			'top':'0px',
			'left':'0px',
			'margin':'0px',
			'padding':'0px',
			'width':pageSize[0],
			'height':pageSize[1],
			'opacity': args.overlayOpacity,
			'background':args.overlayColor		
		});
		
		
		nav.hide();
		overlay.css({'opacity':0});
		overlay.appendTo('body');
		overlay.stop(true).animate({'opacity':args.overlayOpacity}, 300);
		
		// main
		popup.css({'opacity':0});
		popup.empty();		
		popup.appendTo('body');
		popup.stop(true).delay(200).animate({'opacity':1},300, function(){
			nav.right.click(function(){
				cur++;
				if (cur>=contents.length) cur=0;
				popup.play(cur);
			});
			nav.left.click(function(){
				cur--;
				if (cur<0) cur=contents.length-1;
				popup.play(cur);
			});
			nav.x.click(function(){
				popup.hide();
			});
			overlay.click(function(){
				popup.hide();
			});		
						
			popup.play(cur);		
		});
		
	}
	popup.empty = function() {
		popupContent.empty();
	}
	
	popup.hide = function() {
		cur=0;
		popup.remove();
		overlay.remove();		
	}
	
	popup.play = function(i) {
		i = parseInt(i);
		popupContent.stop(true).animate({'opacity':0},200,function(){
			popupContent.empty();
			popupContent.append(contents[i]);
			popupContent.stop(true).animate({'opacity':1},200,function(){
				nav.show();
			});
		});	
	}
	
	
			
	function getPageSize() {
		var xScroll, yScroll;
			if (window.innerHeight && window.scrollMaxY) {	
				xScroll = window.innerWidth + window.scrollMaxX;
				yScroll = window.innerHeight + window.scrollMaxY;
			} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
			} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
			}
			var windowWidth, windowHeight;
			if (self.innerHeight) {	// all except Explorer
				if(document.documentElement.clientWidth){
					windowWidth = document.documentElement.clientWidth; 
				} else {
					windowWidth = self.innerWidth;
				}
				windowHeight = self.innerHeight;
			} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
			} else if (document.body) { // other Explorers
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}	
			// for small pages with total height less then height of the viewport
			if(yScroll < windowHeight){
				pageHeight = windowHeight;
			} else { 
				pageHeight = yScroll;
			}
			// for small pages with total width less then width of the viewport
			if(xScroll < windowWidth){	
				pageWidth = xScroll;		
			} else {
				pageWidth = xScroll;
			}
			arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight);
			return arrayPageSize;
	}
	
	function getPageScroll() {
			var xScroll, yScroll;
			if (self.pageYOffset) {
				yScroll = self.pageYOffset;
				xScroll = self.pageXOffset;
			} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
				yScroll = document.documentElement.scrollTop;
				xScroll = document.documentElement.scrollLeft;
			} else if (document.body) {// all other Explorers
				yScroll = document.body.scrollTop;
				xScroll = document.body.scrollLeft;	
			}
			arrayPageScroll = new Array(xScroll,yScroll);
			return arrayPageScroll;
		};
		
	
	
	
	popupContent.appendTo(popup);
	loading.appendTo(popup);
	nav.hide();	
	nav.left.css({'cursor':'pointer'});
	nav.left.appendTo(popup);
	nav.right.css({'cursor':'pointer'});
	nav.right.appendTo(popup);
	nav.x.css({'cursor':'pointer'});
	nav.hide();
	nav.x.appendTo(popup);
	
	
	
	// start	
	$(window).resize(function(){
		var pageSize = getPageSize();
		var pageScroll = getPageScroll();
		var width = args.width!=0? args.width : contents[cur].width();
		overlay.css({
			'width':pageSize[0],
			'height':pageSize[1]
		});
		popup.css({
			'top' : pageScroll[1]+(pageSize[3]/10)+'px',
			'left': pageScroll[0]+((pageSize[0]-width)/2)+'px'
		});
		
	});
	
	
	
	return popup;
	
	
	
}
