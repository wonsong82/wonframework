var loading = $('<div></div>');
loading.addClass('loading'); 
var param = {};
var module_dir = '';

$(function(){	
	
	module_dir = $('#content').attr('module_dir');	
	$('#content').removeAttr('module_dir');
	
		
	// hash set up
	param.target = '#content';
	// if uri exists, parse it 
	if (window.location.hash != '')
		parse_hash();
	
	// when hash changes, parse it
	window.addEventListener("hashchange", function(){
		parse_hash();
	});


	// navigation
	$('#nav a').click(function(){		
		var module = $(this).attr('href');
		var page = 'admin';
		load_page_hash(module, page, {target:'#content'});		
		return false;
	});	
	
});

function load_page_hash(module, page, param) {
	window.location.hash = module + '/' + page;
	this.param = param;
}


function parse_hash() {
	var uri = (window.location.hash).replace('#','');
		
	if (uri == '') {
		$('#content').html('');
		$('#content').removeAttr('module');
	}
	
	else {
		var uri = uri.split('/');		
		var module = uri[0];
		var page = uri[1];
		load_page(module, page, param);
	}
}



function load_page(module, page, param)
{
	$(param.target).html(loading);
	
	var content = module_dir + '/' + module + '/admin/' + page + '.php';
	
	$.ajax({
		url : content,
		type : 'post',
		async : false,
		cache : false,
		data : param,
		success : function(data) {
			$('#content').attr('module', module);
			$(param.target).html(data);
		}
	});
}




function toggle_help(e){
	if ($(e).find('.sign').html() == '▶')
		$(e).find('.sign').html('▼');
	else
		$(e).find('.sign').html('▶');
		
	var content = $(e).parent().find('div');	
	
	
	if (content.css('display')=='block')
		content.css('display','none');
	else
		content.css('display','block');
	
	return false;
}