(function($){

$.fn.hashAjax=function(){
	
var elements=$(this);
var prevHash=(window.location.hash).replace("#","");
var forceChange=false;

if(elements.length<1){
	throw "A element(s) must be handed.";
	return false;
}

elements.each(function(){
	
	if(!$(this).is("a")){
		throw "Passed elements must be a elements.";
		return false;
	}
	
	if(!$(this).attr("hash")){
		throw "Passed elements must contain 'hash' attributes.";
		return false;
	}
});

var args=arguments[0]||{};
args.callback=args.callback||function(){};
args.loadingOn=args.loadingOn||function(){};
args.loadingOff=args.loadingOff||function(){};

function getContent(hash){
	
	if(forceChange){
		forceChange=false;
		return false;
	}
	
	var found=false;
	elements.each(function(){
		if($(this).attr("hash")==hash){
			var url=$(this).attr("href");
			args.loadingOn();
			
			$.ajax({
				url:$(this).attr("href"),
				cache:false,
				async:true,
				type:"post",
				data:{},
				success:function(data){
					args.loadingOff();
					args.callback(data)
				}
			});
			found=true;
			prevHash=hash;
			return false;			
		}
		
	});
	
	if(!found){
		forceChange=true;
		window.location.hash=prevHash
	}
	return false;
}

window.addEventListener("hashchange",function(){
	var hash=(window.location.hash).replace("#","");
	getContent(hash);
	return false;
});

elements.click(function(){	
	window.location.hash=$(this).attr("hash");
	return false;
});

if(prevHash!=""){
	getContent(prevHash);
}

var o={};
o.refreshElements=function(newCollection){
	elements=newCollection;
	elements.click(function(){
	window.location.hash=$(this).attr("hash");
	return false;
});

}
return o;


}

})(jQuery);