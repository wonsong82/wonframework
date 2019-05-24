(function($){
	
$.fn.imageEditor = function(args){
	
	var img = $(this); // Image
	
	// Load Image First
	$.loadImages([img.attr("src")], function(){
		
		// Parse Args
		args = $.extend({},$.fn.imageEditor.defaultArgs, args);
		
		args.editorWidth = img.width();
		args.editorHeight = img.height();
		
		var jcropApi;
		var values={};
		values.original={
			'rw':parseInt(args.values[0]),
			'rh':parseInt(args.values[1]),
			'x':parseInt(args.values[2]),
			'y':parseInt(args.values[3]),
			'w':parseInt(args.values[4]),
			'h':parseInt(args.values[5])
		};
		values.current={
			'rw':parseInt(args.values[0]),
			'rh':parseInt(args.values[1]),
			'x':parseInt(args.values[2]),
			'y':parseInt(args.values[3]),
			'w':parseInt(args.values[4]),
			'h':parseInt(args.values[5])
		}
		
		// Original Image's Ratio
		args.ratio = args.width / args.height;
		
		// Selection Scale
		args.selectionScale = args.editorWidth / values.original.rw;
		
		var dragging = false;
	
	
		// Structures
		var editor = $('<div class="imageEditor"></div>').appendTo(img.parent());
		var imgCon = $('<div/>').css({
			'position':'relative'
		}).appendTo(editor);
		img.css({
			'display':'block',
			'position':'relative'
		}).appendTo(imgCon);
		var controls = $('<div/>').css({
			'position':'relative'
		}).appendTo(editor);
		$('<br/><span>Original Size:</span>').appendTo(controls);
		$('<span>' + args.width + '</span>').css({
			'width':'40px',
			'display':'inline-block',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).appendTo(controls);
		$('<span>x</span>').appendTo(controls);
		$('<span>' + args.height + '</span>').css({
			'width':'40px',
			'display':'inline-block',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).appendTo(controls);
		$('<span>px</span>').appendTo(controls);
		$('<br/><br/><span>Resize to:</span>').appendTo(controls);
		// Resized Width
		controls.rw = $('<input type="text" class="rw control" key="rw" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.rw).appendTo(controls);
		$('<span>x</span>').appendTo(controls);
		// Resized Height
		controls.rh = $('<input type="text" class="rh control" key="rh" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.rh).appendTo(controls);
		$('<span>px</span>').appendTo(controls);
		$('<br/><br/><span>Selection Position: x</span>').appendTo(controls);
		// Selection X
		controls.x = $('<input type="text" class="x control" key="x" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.x).appendTo(controls);
		$('<span>, y</span>').appendTo(controls);
		// Selection Y
		controls.y = $('<input type="text" class="y control" key="y" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.y).appendTo(controls);
		$('<span> | </span>').appendTo(controls);
		$('<span>Selection Size:</span>').appendTo(controls);
		// Selection Width
		controls.w = $('<input type="text" class="w control" key="w" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.w).appendTo(controls);
		$('<span>x</span>').appendTo(controls);
		// Selection Height
		controls.h = $('<input type="text" class="h control" key="h" />').css({
			'width':'30px',
			'font-weight':'bold',
			'text-align':'center',
			'margin':'0px 3px'
		}).val(values.current.h).appendTo(controls);
		$('<span>px</span><br/><br/>').appendTo(controls);
		controls.update = $('<button>UPDATE</button>').appendTo(controls);
		controls.cancel = $('<button>RESET</button>').appendTo(controls);
		
		// Functions and Events
		function setValues(c){
			var scale = values.current.rw / args.editorWidth;
			values.current.x = Math.floor(c.x * scale);
			values.current.y = Math.floor(c.y * scale);
			values.current.w = Math.floor(c.w * scale);
			values.current.h = Math.floor(c.h * scale);
			controls.x.val(values.current.x);
			controls.y.val(values.current.y);
			controls.w.val(values.current.w);
			controls.h.val(values.current.h);
		};
		
		function onChange(c){
			if(dragging==true){
				var scale = values.current.rw / args.editorWidth;
				
				
				values.current.x = Math.ceil(c.x * scale);
				values.current.y = Math.ceil(c.y * scale);
				values.current.w = Math.ceil(c.w * scale);
				values.current.h = Math.ceil(c.h * scale);
				controls.x.val(values.current.x);
				controls.y.val(values.current.y);
				controls.w.val(values.current.w);
				controls.h.val(values.current.h);
			}
		};
		
		function setSelect(){
			var scale = args.editorWidth / values.current.rw;
			var s = [
				Math.floor(values.current.x * scale),
				Math.floor(values.current.y * scale),
				Math.floor((values.current.x + values.current.w) * scale),
				Math.floor((values.current.y + values.current.h) * scale)				
			];
			for(var i=0;i<s.length;i++)
				if(isNaN(s[i])) s[i] = 0;
			jcropApi.setSelect(s);
		};
		//Restrict Numbers
		$('.control', editor).keypress(function(e){
			if(e.keyCode > 31 && (e.keyCode < 48 || e.keyCode > 57))
				return false;		
		}); 
		$('.control', editor).blur(function(){
			if(isNaN(parseInt($(this).val()))){
				$(this).val(0);
				var key = $(this).attr("key");
				values.current[key] = 0;	
				setSelect();		
			}					
		});
		
		controls.rw.blur(function(){	
			var scale = controls.rw.val() / values.current.rw;
			values.current.rw = parseInt(controls.rw.val());
			if(isNaN(values.current.rw)) return false;
			values.current.rh = Math.floor(values.current.rw / args.ratio);
			values.current.x = Math.floor(values.current.x * scale);
			values.current.y = Math.floor(values.current.y * scale);
			values.current.w = Math.floor(values.current.w * scale);
			values.current.h = Math.floor(values.current.h * scale);			
			controls.rh.val(values.current.rh);
			controls.x.val(values.current.x);
			controls.y.val(values.current.y);
			controls.w.val(values.current.w);
			controls.h.val(values.current.h);			
			setSelect();		
		});
		
		controls.rh.blur(function(){
			var scale = controls.rh.val() / values.current.rh;
			values.current.rh = parseInt(controls.rh.val());
			if(isNaN(values.current.rh)) return false;
			values.current.rw = Math.ceil(values.current.rh * args.ratio);
			values.current.x = Math.ceil(values.current.x * scale);
			values.current.y = Math.ceil(values.current.y * scale);
			values.current.w = Math.ceil(values.current.w * scale);
			values.current.h = Math.ceil(values.current.h * scale);			
			controls.rw.val(values.current.rw);
			controls.x.val(values.current.x);
			controls.y.val(values.current.y);
			controls.w.val(values.current.w);
			controls.h.val(values.current.h);			
			setSelect();
		});
		
		controls.x.keyup(function(){
			values.current.x = parseInt(controls.x.val());
			if(isNaN(values.current.x)) return false;
			if(values.current.x > values.current.rw){
				values.current.x = values.current.rw;
				controls.x.val(values.current.x);
			}
			if(values.current.x + values.current.w > values.current.rw){
				values.current.w = values.current.rw - values.current.x;
				controls.w.val(values.current.w);
			}
			setSelect();
		});
		
		controls.y.keyup(function(){
			values.current.y = parseInt(controls.y.val());
			if(isNaN(values.current.y)) return false;
			if(values.current.y > values.current.rh){			
				values.current.y = values.current.rh;
				controls.y.val(values.current.y);
			}
			if(values.current.y + values.current.h > values.current.rh){
				values.current.h = values.current.rh - values.current.y;
				controls.h.val(values.current.h);
			}
			setSelect();
		});
		
		controls.w.keyup(function(){
			values.current.w = parseInt(controls.w.val());
			if(isNaN(values.current.w)) return false;
			if(values.current.w > values.current.rw - values.current.x){
				values.current.w = values.current.rw - values.current.x;
				controls.w.val(values.current.w);
			}		
			setSelect();
		});
		
		controls.h.keyup(function(){
			values.current.h = parseInt(controls.h.val());
			if(isNaN(values.current.h)) return false;
			if(values.current.h > values.current.rh - values.current.y){
				values.current.h = values.current.rh - values.current.y;
				controls.h.val(values.current.h);
			}		
			setSelect();
		});
		
		controls.update.click(function(){
			args.update(values.current);
		});
		
		controls.cancel.click(function(){
			for(var key in values.original){
				values.current[key] = values.original[key];
				controls[key].val(values.current[key]);
			}
			setSelect();		
		});
		
		
		// Set Image Selection
		img.Jcrop({
			'setSelect':[
				Math.floor(values.current.x * args.selectionScale),
				Math.floor(values.current.y * args.selectionScale),
				Math.floor((values.current.x + values.current.w) * args.selectionScale),
				Math.floor((values.current.y + values.current.h) * args.selectionScale)
			],
			'onSelect':setValues,
			'onChange':onChange
		}, function(){
			jcropApi = this;
			$('.jcrop-holder *', editor).mousedown(function(){
				dragging = true;
			});
			$('.jcrop-holder *', editor).mouseup(function(){
				dragging = false;
			});
		});
		
		
	});
};	
	


$.fn.imageEditor.defaultArgs={
	'width':0, // Original Image Width
	'height':0, // Original Image Height
	'values':[0,0,0,0,0,0], // Resized(w,h), Selection(x, y, w, h)
	'update':function(){} // When Update is clicked
};
	
})(jQuery);