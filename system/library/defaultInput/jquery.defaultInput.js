/*
Default input reads default value from the inputs handed and display it as a default value
group of elements must be handed in
*/
$.fn.defaultInput=function(){
	
	//Check for value handed
	if($(this).length<1){
		throw "No Elements passed";
		return false;
	}
	
	//Define
	var $elements=$(this);
	var $args=arguments[0]||{};
	
	
	$elements.each(function(){
		
		var $this=$(this);
		var $default=$this.attr('default');
		var $color=$this.css('color');
		var $mask=$this.attr('mask');
				
		// if text
		if($default){
			var $realObj=$this;
			var $display=$this.css('display');
			
			var $fakeObj=null
			if($realObj.is('input[type="text"]'))
				$fakeObj=$('<input type="text"/>');
			else if($realObj.is('input[type="password"]'))
				$fakeObj=$('<input type="text"/>');
			else if($realObj.is('textarea'))
				$fakeObj=$('<textarea />');
			
			if($fakeObj==null){
				throw "only supports text input, password input, textarea";
				return false;
			}
						
			$.each($realObj.get(0).attributes, function(i,attr){
				if (attr.name!='name'&&attr.name!='id'&&attr.name!='type')
					$fakeObj.attr(attr.name, attr.value);
			});	
			
			$realObj.css('display','none');
			$fakeObj.addClass('inactive').val($default).insertAfter($realObj);
			
			if($mask){
				$realObj.mask($mask);
			}
							
			$fakeObj.focus(function(){
				if($fakeObj.val()==$default){
					$fakeObj.css('display','none');
					$realObj.val('').css('display',$display);
					$realObj.focus();
				}
			});
			$realObj.blur(function(){
				if($realObj.val().trim()==''){
					$realObj.css('display','none');
					$fakeObj.css('display',$display);
				}
			});
		}	
	});
}