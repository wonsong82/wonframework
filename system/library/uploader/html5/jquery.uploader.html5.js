(function($){

$.fn.html5uploader=function(args){
	
	var $this = $(this); // Button
	
	// Parse Args
	args = $.extend({},$.fn.html5uploader.defaultArgs,args);
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
		
		
		var form = $('<div/>').css({
			'width':'260px',
			'height':'100px',
			'background':'#fff',
			'position':'fixed',
			'top':'50%',
			'left':'50%',
			'margin-top':'-50px',
			'margin-left':'-130px',
			'padding':'20px',
			'border-radius':'4px',
			'opacity':.8
		}).appendTo(uploaderContainer);
		
		var fileInput = $('<input type="file" accept="'+args.accept+'"/>').css({
			'width':'auto',
			'margin':'0px'
		}).appendTo(form);
		
		var filename = $('<div/>').css({
			'font':'12px/12px arial',
			'margin':'5px',
			'text-align':'center',
			'width':'260px',
			'overflow':'hidden'
		}).appendTo(form);
				
		var progress = $('<div/>').css({
			//'display':'none',
			'position':'relative',
			'width':'260px',
			'height':'20px',
			'border':'1px solid #ccc',
			'text-align':'center',
			'display':'none'
		}).appendTo(form);
		
		var bar = $('<div/>').css({
			'position':'absolute',
			'top':'0px','left':'0px',
			'width':'0px','height':'20px',
			'background':'#09f'
		}).appendTo(progress);
		
		var percent = $('<span/>').css({
			'position':'relative',
			'font':'12px/12px arial'
		}).appendTo(progress);
		
		var info = $('<div/>').css({
			'position':'absolute',
			'right':'20px',
			'top':'100px',
			'font':'12px/12px arial',
			'text-align':'right',
			'display':'none'
		}).appendTo(form);
		var elapsedLabel = $('<div>Elapsed </div>').css({
			'margin-top':'3px'
		}).appendTo(info);
		var elapsed = $('<span/>').appendTo(elapsedLabel);		
		var remainingLabel = $('<div>Remaining </div>').appendTo(info);
		var remaining = $('<span/>').appendTo(remainingLabel);
		
						
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
			'margin-top':'-70px'
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
		
		fileInput.css('display','block');
		closeButton.css('display','block');
		
		
		
		// When File is Changed
		fileInput.unbind("change");
		fileInput.change(function(){
			
			//Disable Controls
			closeButton.css('display','none');
			$(document).unbind("keyup");
			fileInput.css('display','none');
			
			var file = $(this).prop("files")[0];
			var reader = new FileReader();
			filename.html('File: '+file.name +' ('+Math.floor((file.size)*0.001)+' KB)');
			
			var i=0;
			var chunkSize = args.chunk;
			var numChunk = Math.floor(file.size/chunkSize);
			if(file.size%chunkSize!=0) numChunk++;
			var startTime = Date.now();
			
			function readBlob(){
				var from = i*chunkSize;
				var end = i==numChunk-1? from+(file.size%chunkSize) : from+chunkSize;
				var blob = file.slice(from, end);
				reader.readAsDataURL(blob);
			}		
			
			reader.onloadend = function(e){
				var first = i==0? 1:0;
				var last = i==numChunk-1? 1:0;
				if(first){ 
					progress.css('display','block');
					bar.width(0);
					percent.html('0%');
					info.css('display','block');
				}
				if(e.target.readyState==FileReader.DONE){
					var data = e.target.result;
					
					$.ajax({
						'url':args.action,
						'cache':false,
						'type':'post',
						'async':false,
						'data':{
							'name':file.name,
							'index':i,
							'first':first,
							'last':last,
							'data':data
						},
						'success':function(d){						
							var percentLoaded = Math.floor(((i+1)/numChunk)*100);
							bar.width(percentLoaded+'%');
							percent.html(percentLoaded+'%');
							var now = Date.now();
							var elap={};
							elap.time = now - startTime;
							elap.sec = Math.floor((elap.time/1000)%60);
							if(elap.sec<10) elap.sec = '0'+elap.sec;
							elap.min = Math.floor((elap.time/1000)/60);
							var estimated = ((numChunk)*elap.time)/(i+1);
							var remain={};
							remain.time = Math.floor((estimated - elap.time)/1000);
							remain.sec = remain.time%60;
							if(remain.sec<10) remain.sec='0'+remain.sec;
							remain.min = Math.floor(remain.time/60);
							remaining.html(remain.min+':'+remain.sec);
							elapsed.html(elap.min+':'+elap.sec);
							
							if(!last){
								i++;
								readBlob();
							}
							else {
								args.success(d);
								uploaderContainer.remove();								
							}
							
						}
					});
				}
				
			}
			
			readBlob();
			return false;
		});
		
	
		
		return false;
	});
	
	
	
		
	
	// Structures
	
	
};

$.fn.html5uploader.defaultArgs={
	'accept':"audio/*,video/*,image/*",
	'success':function(){},
	'action':null,
	'chunk':50000	
}
	
})(jQuery);