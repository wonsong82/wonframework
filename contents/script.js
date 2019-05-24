


/* menu */	
$(function(){
	
	$('#imgmenu li').each(function(){
		var bg = $('<div></div>');
		bg.css({width:$(this).width(), height:$(this).height(),opacity:.6,position:'absolute',top:'0px',left:'0px'});
		bg.addClass('bg');
		var text = $(this).find('div.text');
		text.prepend(bg);
		text.find('h3').css({position:'relative'});
		text.find('p').css({position:'relative'});
		var textY = text.position().top;
		$(this).hover(function(){
			text.stop(true).animate({top:'0px'}, 200, 'easeOutQuart');
		}, function(){
			text.stop(true).animate({top:textY}, 700, 'easeOutQuart');
		});
		$(this).css({'cursor':'pointer'});		
	});
	
	$('.scroll').jScrollPane();
});



/* Contact us */
$(function(){
	
	// submit button
	var submitBtn = $('.contact #contact-us-form input:submit');
	var submitBtnColor = submitBtn.css('background-color');
	submitBtn.hover(function(){
		$(this).css('background-color','#dddddd');
	}, function(){
		$(this).css('background-color',submitBtnColor);
	});
	
	$('.contact #contact-us-form').submit(function(){
		submitBtn.hide();
		var form = $(this);
		$.ajax({
			type:'post',
			url:form.attr('action'),
			cache:false,
			async:true,
			data:{
				name:$('#c-name').val(),
				email:$('#c-email').val(),
				phone:$('#c-phone').val(),
				subject:$('#c-subject').val(),
				message:$('#c-message').val(),
				subscribe:form.find('input:radio[name=c-subscribe]:checked').val()
			},
			success:function(data){
				if (data=='ok') {
					data='<span style="color:#09A2DB">Your message has been sent</span>';
				} else {
					data='<span style="color:#ff0000">'+data+'</span>';
					submitBtn.show();					
				}
				form.find('#c-msgbox').html(data).stop(true).css({'display':'block','opacity':0}).animate({'opacity':1},500,function(){$(this).animate({'opacity':1},5000,function(){$(this).animate({'opacity':0},1000,function(){$(this).css('display','none');});});});
				
			}
		});		
		return false;
	});
	
});


/* Feedback */
$(function(){
	
	// submit button
	var submitBtn = $('.feedback #feedback-form input:submit');
	var submitBtnColor = submitBtn.css('background-color');
	submitBtn.hover(function(){
		$(this).css('background-color','#dddddd');
	}, function(){
		$(this).css('background-color',submitBtnColor);
	});
	
	$('.feedback #feedback-form').submit(function(){
		submitBtn.hide();
		var form = $(this);
		$.ajax({
			type:'post',
			url:form.attr('action'),
			cache:false,
			async:true,
			data:{
				name:$('#f-name').val(),
				feedback:$('#f-feedback').val(),
				display:$('#f-display').val()
			},
			success:function(data){
				if (data=='ok') {
					data='<span style="color:#09A2DB">Your feedback has been submitted for a manual review.</span>';
				} else {
					data='<span style="color:#ff0000">'+data+'</span>';
					submitBtn.show();					
				}
				form.find('#f-msgbox').html(data).stop(true).css({'display':'block','opacity':0}).animate({'opacity':1},500,function(){$(this).animate({'opacity':1},5000,function(){$(this).animate({'opacity':0},1000,function(){$(this).css('display','none');});});});
				
			}
		});		
		return false;
	});
	
	// view more
	var numShow = 4;
	var start = 0;
	var feedbacks = $('.feedback #feedbacks li');
	feedbacks.each(function(){
		$(this).css('display','none');
	});
		
	function showNext() {
		for (var i=start; i<start+numShow; i++) {
			if (i<=feedbacks.length-1) {
				feedbacks.eq(i).css('display','block');
			}
		}
		start+=4;
		if (start>=feedbacks.length) {
			$('#more-feedback').css('display','none');
		}	
	}
	
	$('#more-feedback a').click(function(){
		showNext();
		return false;
	});
	
	showNext();
	//alert(feedbacks.eq().html());
	
});