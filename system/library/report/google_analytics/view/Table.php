<div id="google-analytics-table">	
</div>
<script>
google.load("visualization", "1", {packages:["table"], callback:drawChart});
function drawChart(){
	var data = new google.visualization.DataTable(<?=$data;?>);
	
	var options = {
		title: 'Title'
	}	
	
	var table = new google.visualization.Table(document.getElementById('google-analytics-table'));
	table.draw(data, {showRowNumber: false});
}
</script>