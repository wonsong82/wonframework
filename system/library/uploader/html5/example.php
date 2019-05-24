<!doctype html>
<html>
<head>
<script src="../jquery/jquery-1.7.1.min.js"></script>
<script src="jquery.uploader.html5.js"></script>
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
$('#uploader').html5uploader({
	'action':'uploader.php',
	'success':function(d){
		$('#msg').html(d);
	}
});
});
</script>
</body>
</html>
