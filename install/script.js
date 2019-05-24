var config = {};

var loading = {
	on : function() {
		$('#loading').addClass('loading');
	} ,
	off : function() {
		$('#loading').removeClass('loading');
	}
}


$(function(){	
	
	config.progress = 0;
		
	window.addEventListener("hashchange", function(){		
		loadTemplate('templates/' + (window.location.hash).replace('#','') + '.php');
	});
	
	if (window.location.hash == '')
		window.location.hash = 'welcome';
	else
		loadTemplate('templates/' + (window.location.hash).replace('#','') + '.php');
	
	
});

function loadTemplate(file, param) {
	loading.on();
	$.ajax({
		url : file,
		cache : false,
		async : true,
		type : 'post',
		data : config,
		success : function (data) {
			$('#content').css('opacity',0);
			$('#content').html(data);			
			$('#content').stop(true).animate({'opacity':1}, 1000);
			loading.off();
		}
	});	
}

function changeHash(e){
	config.progress = $(e).attr('progress');
	window.location.hash = $(e).attr('next');	
}

