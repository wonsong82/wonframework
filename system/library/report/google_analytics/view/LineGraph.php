<div id="google-analytics-linegraph">	
</div>
<script>
google.load("visualization", "1", {packages:["corechart"], callback:drawChart});
function drawChart(){
	var data = new google.visualization.DataTable(<?=$data;?>);
	
	
	var options = {
		title: 'Title'
		
	}
	
	var chart = new google.visualization.LineChart(document.getElementById('google-analytics-linegraph'));
	chart.draw(data, options);
	
	
}
</script>