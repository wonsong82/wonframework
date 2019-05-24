<!doctype html>
<html>
<head>
<script src="../jquery/jquery-1.7.1.min.js"></script>
<script src="jquery.uploader.iframe.js"></script>
<style>
.loading{
	width:20px;height:20px;background:#fff;
}
</style>
</head>


<body>
<button id="uploader">Upload File</button>
<div id="msg"></div>
<script>
$(function(){
$('#uploader').uploader({
	'action':'uploader.php',
	'limit':'<?=ini_get('upload_max_filesize');?>',
	'success':function(d){
		$('#msg').html(d);
	}
});
});
</script>
</body>
</html>
