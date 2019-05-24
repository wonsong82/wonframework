/****************************************************
Sticky Footer

Formats are following :
<html>
	<body>
		<#body-wrap>
			<#bodyElement />
			<#lastBodyElement />	
		</#body-wrap>
		<footerElementPassedIn>
		</footerElementPassedIn>
	</body>
</html>	

html, body {
	height:100%;
}
#body-wrap {
	min-height:100%;
}
#lastBodyElement {
	overflow:auto;
	padding-bottom:180px; // must be same height as the footer
}  
#footerElementPassedIn {
	position:relative;
	margin-top:-180px; // negative value of footer height
	height:180px;
	clear:both;
}
//Opera Fix
body:before {
	content:"";
	height:100%;
	float:left;
	width:0;
	margin-top:-32767px;
}
***************************************************************/
(function($){

$.fn.stickyFooter=function(args){
	
	// Variables
	var $body=$('body'); //Entire body
	var $footer=$(this); //Passed in div is footer
	var $wrapper=$('<div/>');
	
	// Parse args
	args=$.extend({}, $.fn.stickyFooter.defaultArgs, args);
	
	// Set structures
	$('html,body')
	.css({
		height:'100%'
	});
	
	$wrapper
	.attr('id', 'body-wrap')
	.css({
		'min-height': '100%'
	});
			
	$('body')
	.wrapInner($wrapper);
	
	$footer
	.css({
		'position': 'relative',
		'margin-top': -1 * args.footerHeight + 'px',
		'height': args.footerHeight + 'px',
		'clear': 'both'
	})
	.appendTo('body');
	
	$('>*:last', '#body-wrap')
	.css({
		'overflow': 'auto',
		'padding-bottom': args.footerHeight + 'px'
	});
	
	// Opera Fix
	if($.browser.opera){
		
		$('body:before')
		.css({
			'content': '',
			'height': '100%',
			'float': 'left',
			'width': '0px',
			'margin-top': '-32767px'
		});
	}
}

$.fn.stickyFooter.defaultArgs={
	footerHeight:100
}
	
})(jQuery);