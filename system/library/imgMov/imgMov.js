/* 
Control of sequence of img files (jpg/png/gif).

passed object must be img tag.
req args - 
	type : jpg, png, gif
 	frames : total number of frames
	fps : frames per second
	loop : bool, restart after finish

sequence of imgs must be presented within a folder named '{image}_seq' as numbers starting from 1 as filename.
for instance, if a passed img tag has dummy.jpg, 
dummy_seq folder must be presented and has the same numbers of imgs defined in frames arg, named 1.jpg ~ endnum.jpg

*/
(function($){
$.fn.imgMov=function(){
	
	var args=arguments[0]||{};
	args.type=args.type||'jpg';
	args.frames=parseInt(args.frames)||false;
	args.fps=parseFloat(args.fps)||false;
	args.loop=args.loop||false;
	
	if(args.type!='jpg'&&args.type!='gif'&&args.type!='png'){
		throw "Supports types : jpg, png, gif";
		return false;
	}
	if(!args.frames){
		throw "Number of frames must be defined";
		return false;
	}
	if(!args.fps){
		throw "FPS must be defined";
		return false;
	}
	
	var o=$(this[0]);
	
	o.timer=null;
	if(!o.is('img')){
		throw "IMG element must be passed";
		return false;
	}
	if($.isFunction($.loadImages)!=true){//Check for loadImages function
		throw "jquery.loadImages must be loaded first.";
		return false;
	}
	
	args.ms=parseInt(1000/args.fps);
	
	var imgsToLoad=[];
	var imgs=[];
	var cur=0;
	
	var imgCover=o.attr('src');
	var seqDir=imgCover.replace('.'+args.type,'')+'_seq';
	for(var i=1;i<=args.frames;i++){
		imgs.push(seqDir+'/'+i+'.'+args.type);
	}
	
	o.load=function(func){
		$.loadImages(imgs, func);
	}
	
	o.play=function(){
		cur=0; //reset
		clearInterval(o.timer);
				
		o.attr('src',imgs[cur]);
		
		
		o.timer = setInterval(function(){
			
			cur++;
			if(args.loop){//loop
				if(cur>imgs.length-1)
					cur=0;
			}else{//if not loop
				if(cur>imgs.length-1){
					cur=imgs.length-1;
					clearInterval(o.timer);					
				}
			}
			
			o.attr('src',imgs[cur]);
									
			
		}, args.ms);
	}
	
	o.rewind=function(){
		clearInterval(o.timer);
		
		o.attr('src',imgs[cur]);
		
		o.timer = setInterval(function(){
			
			cur--;
			if(args.loop){//loop
				if(cur<0){
					cur=imgs.length-1;
				}
			} else { // if not loop
				if(cur<0){
					cur=0;
					clearInterval(o.timer);
					
				}
			}
			
			o.attr('src', imgs[cur]);
			
			
		}, args.ms);
	}
	
	o.resume=function(){
		clearInterval(o.timer);
		
		//o.attr('src',imgs[cur]);
		o.timer = setInterval(function(){
			o.attr('src',imgs[cur]);
			
			cur++;
			if(args.loop){//loop
				if(cur>imgs.length-1)
					cur=0;
			}else{//if not loop
				if(cur>imgs.length-1){
					cur=imgs.length-1;
					clearInterval(o.timer);					
				}
			}						
			
		}, args.ms);
	}
	
	o.stop=function(){
		clearInterval(o.timer);
		cur=0;		
	}
	
	o.pause=function(){
		clearInterval(o.timer);
	}
	
	
	
	return o;
}	
})(jQuery);