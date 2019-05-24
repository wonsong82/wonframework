// loading image, intended being used throughout the whole installation process
var loading = $('<img src="images/loading.gif" />'); 

// set this $progress when the current form is completed and good to go to the next
var progress = 0;

// setup templates in order
var templates = [
		'welcome',
		'requirement',
		'dbsetup',
		'adminsetup',
		'finish'
	];


$(function(){

	// load all the templates and put it append them into content
	load_templates(templates);	


	// when hash_changed, act accordingly
	$(window).bind('hashchange', function(){		
		show_tab(window.location.hash);		
	});
	
	// set up the welcome hash
	if (window.location.hash == '')
		window.location.hash = 'welcome';
	else
		show_tab(window.location.hash);
});


function load_templates(templates) {
	
	for (var i in templates) {
		$('#loading').html(loading);
		$.ajax({
			url : 'templates/'+templates[i]+'.template.php',
			cache : false,
			async : false,
			success : function(data){
				$('#loading').html('');
				$('#content').append('<div id="'+templates[i]+'" class="tab" style="display:none">'+data+'</div>');
			}
		});
	}	
}


function hash_change(e){
	progress = $(e).attr('progress');
	window.location.hash = $(e).attr('hashtag');	
}

function show_tab(id){
	var cur;
	for (var i=0; i<templates.length; i++){
		if (id.replace('#', '') == templates[i])
			cur = i;
	}
	
	if (cur > progress)
		window.location.hash = templates[progress];
	
	else{	
		$('.tab').css('display','none');
		$(id).css({'opacity':0, 'display':'block'});
		$(id).stop(true).animate({'opacity':1}, 1000);
	}
}

