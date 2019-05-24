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
	
	//Parse Args
	var $inactive=$args.inactive||'#AAAAAA';
	
	$elements.each(function(){
		
		var $this=$(this);
		var $default=$this.attr('default');
		var $color=$this.css('color');
			
		// if text, textarea
		if($this.is('input[type="text"]')||$this.is('textarea')){
			$this.css('color',$inactive);
			$this.val($default);
			$this.focus(function(){
				if($this.val()==$default){
					$this.val('');
					$this.css('color',$color);
				}
			});
			$this.blur(function(){
				if($this.val().trim()==''){
					$this.val($default);
					$this.css('color',$inactive);
				}
			});
		}
		
		// if password
		if($this.is('input[type="password"]')){
			var $passInput=$this;
			var $display=$this.css('display');
			var $textInput=$('<input type="text"/>').attr('default',$default).css('color',$inactive).val($default);
			$textInput.insertAfter($passInput);
			$passInput.css('display','none');
			$textInput.focus(function(){
				if($textInput.val()==$default){
					$textInput.css('display','none');
					$passInput.val('').css('display',$display);
					$passInput.focus();
				}
			});
			$passInput.blur(function(){
				if($passInput.val().trim()==''){
					$passInput.css('display','none');
					$textInput.css('display',$display);
				}
			});
		}	
	});
}