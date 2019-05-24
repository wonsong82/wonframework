<?php
require_once dirname(__FILE__) . '/controller/GoogleAnalyticsController.php';
$ga = new GoogleAnalyticsController('info@nemonyc.com','nemonyc1177','ga:39811272');

?>

<html>
<head>
	<script src="https://www.google.com/jsapi"></script>
</head>
<body>
<?php
$ga->printLineGraph(array(
	'Date'=>'ga:week',
	'Visits'=>'ga:visits',
	'Bounce Rate'=>'ga:visitBounceRate',
	'AVG Time'=>'ga:avgTimeOnSite',
	'Reservation'=>'ga:goal1Completions'
), 'Date');

?>

<?php
$ga->printTable(array(
	'Date'=>'ga:week',
	'Visits'=>'ga:visits',
	'Bounce Rate'=>'ga:visitBounceRate',
	'AVG Time'=>'ga:avgTimeOnSite',
	'Reservation'=>'ga:goal1Completions'
), 'Date', 'desc');
?>

</body>
</html>	






