var loading = $('<img src="images/loading.gif" />'); 


$(function(){	
	// navigation
	$('#nav a').click(function(){
		
		$('#content').html(loading);
		var module = $(this).attr('href');
		var module_class = $(this).attr('module_class');
		var admin_file = module + '/admin_menu.php';
		$.ajax({
			url : admin_file,
			type : 'post',
			async : false,
			cache : false,
			success : function(data) {
				
				$('#content').attr('module' , module);				
				$('#content').html(data);
				
				$.ajax({
					url : 'controllers/phpdoc.php',
					type : 'post',
					async : true,
					cache : false,
					data : {
						class : module_class
					},
					success : function(data) {
						$('#content').append(data);
					}
				});				
			}
		});
		
		return false;
	});	
	
});


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