(function($){

$.fn.uploader=function(args){
	
	var $this = $(this); // Button
	
	// Parse Args
	args = $.extend({},$.fn.uploader.defaultArgs,args);
	args.name = $this.attr('id');
	if(!args.action){
		throw "PHP File Handler is missing";
		return false;
	}
	
	
	// Functions
	$this.click(function(){
		
		var uploaderContainer = $('<div id="'+args.name+'"></div>');
	
		var bg = $('<div/>').css({ // Opaque BG
			'position':'fixed',
			'width':'100%',
			'height':'100%',
			'background':'#000',
			'opacity':0.5,
			'top':'0px',
			'left':'0px'
		}).appendTo(uploaderContainer);
		
		var form = $('<form/>').css({
			'width':'260px',
			'height':'30px',
			'background':'#fff',
			'position':'fixed',
			'top':'50%',
			'left':'50%',
			'margin-top':'-20px',
			'margin-left':'-130px',
			'padding':'20px',
			'border-radius':'4px',
			'opacity':.8
		}).appendTo(uploaderContainer);
		
		var label = $('<label/>').css({
			'font':'12px/12px arial',
		}).html('Size Limit: '+args.limit).appendTo(form);
		
		var fileInput = $('<input type="file" name="file" accept="'+args.accept+'"/>').css({
			'width':'auto',
			'margin':'0px'
		}).appendTo(form);
			
		var closeButton = $('<div>X</div>').css({
			'width':'15px',
			'height':'15px',
			'color':'#fff',
			'font':'bold 15px/15px arial',
			'cursor':'pointer',
			'margin':'0px',
			'padding':'0px',
			'position':'fixed',
			'left':'50%',
			'margin-left':'160px',
			'top':'50%',
			'margin-top':'-40px'
		}).appendTo(uploaderContainer);
		
		$('body').append(uploaderContainer);
		
		// Enable Controls
		closeButton.unbind("click");
		closeButton.click(function(){
			uploaderContainer.remove();
			return false;
		});
		
		$(document).unbind("keyup");
		$(document).keyup(function(e){
			if(e.keyCode==27){
				uploaderContainer.remove();
			}
			return false;
		});
		
		form.css('display','block');
		closeButton.css('display','block');
		
		
		
		// When File is Changed
		fileInput.unbind("change");
		fileInput.change(function(){
			
			// Loading
			var loading = $('<div/>').addClass('loading').css({
				'position':'fixed',
				'top':'50%',
				'left':'50%'
			}).appendTo(uploaderContainer);
			
			//Disable Controls
			form.css('display','none');
			closeButton.css('display','none');
			$(document).unbind("keyup");
			$('#'+args.name+'_iframe').remove();
			
			//Make Iframe			
			var iframe = $('<iframe width="200" height="200" border="0" name="'+args.name+'_iframe" id="'+args.name+'_iframe"></iframe>').css({
				'display':'none'
			}).appendTo(uploaderContainer);
			var frame = document.getElementById(args.name+'_iframe');
			var content='';
			if(frame.addEventListener){
				frame.addEventListener('load',function(){
					if(frame.contentDocument){
						content = frame.contentDocument.body.innerHTML;
					}
					else if(frame.contentWindow){
						content = frame.contentWindow.document.body.innerHTML;
					}
					else if(frame.document){
						content = frame.document.body.innerHTML;
					}
					args.success(content);
					uploaderContainer.remove();				
				});
			}
			else if(frame.attachEvent){
				frame.attachEvent('onload',function(){
					if(frame.contentDocument){
						content = frame.contentDocument.body.innerHTML;
					}
					else if(frame.contentWindow){
						content = frame.contentWindow.document.body.innerHTML;
					}
					else if(frame.document){
						content = frame.document.body.innerHTML;
					}
					args.success(content);
					uploaderContainer.remove();
				});
			}
			
			// Set Form
			form.attr('target', args.name+'_iframe');
			form.attr('action', args.action);
			form.attr('method','post');
			form.attr('enctype','multipart/form-data');
			form.attr('encoding','multipart/form-data');
			form.submit();
			
			return false;
		});
		
	
		
		return false;
	});
	
	
	
		
	
	// Structures
	
	
};

$.fn.uploader.defaultArgs={
	'accept':"audio/*,video/*,image/*",
	'success':function(){},
	'action':null,
	'limit':'Undefined'
}
	
})(jQuery);