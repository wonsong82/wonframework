<?php
class GoogleAnalyticsController
{
	public $ga;
	public $dates;
	
	public function __construct($email, $password, $profile)
	{
		require_once dirname(dirname(__FILE__)) . '/class/googleanalytics.class.php';
		$ga = new GoogleAnalytics($email, $password);
		$ga->setProfile($profile);
		$this->ga = $ga;
		
		$from = date('Y') .'-01-01';
		$to = date('Y-m-d');
			
		$this->setRange($from, $to);		
	}
	
	public function setRange($from, $to)
	{
		$this->dates = array('from'=>$from, 'to'=>$to);
		$this->ga->setDateRange($from, $to);
	}		
	
	
	public function createData($columns, $properties, $convert)
	{				 
		return $this->ga->getReport($columns, $properties, $convert);
			
	}
	
	public function printLineGraph($fields, $sort, $ascending='asc')
	{
		$cols = array();
		$dim; $metrics=array(); $sort;
		
		// dimensions & metrics
		foreach ($fields as $fieldName=>$fieldValue) {
			$cols[] = $fieldName;
			if (count($cols)==1)
				$dim = $fieldValue;
			else
				$metrics[] = $fieldValue;			
		}
		
		// sort
		$sort = $ascending=='asc'? $fields[$sort] : '-'.$fields[$sort];
		
		$properties = array();
		$properties['dimensions'] = urlencode($dim);
		$properties['metrics'] = urlencode(implode(',',$metrics));
		$properties['sort'] = $sort;
		
		$data = $this->createData($cols, $properties, false);
		$data = json_encode($data);	
		
		require_once dirname(dirname(__FILE__)) . '/view/LineGraph.php';		
	}
	
	public function printTable($fields, $sort, $ascending='asc')
	{
		$cols = array();
		$dim; $metrics=array(); $sort;
		
		// dimensions & metrics
		foreach ($fields as $fieldName=>$fieldValue) {
			$cols[] = $fieldName;
			if (count($cols)==1)
				$dim = $fieldValue;
			else
				$metrics[] = $fieldValue;			
		}
		
		// sort
		$sort = $ascending=='asc'? $fields[$sort] : '-'.$fields[$sort];
		
		$properties = array();
		$properties['dimensions'] = urlencode($dim);
		$properties['metrics'] = urlencode(implode(',',$metrics));
		$properties['sort'] = $sort;
		
		$data = $this->createData($cols, $properties, true);
		$data = json_encode($data);
		
		require_once dirname(dirname(__FILE__)) . '/view/Table.php';
		
		/* example
		$ga->printTable(array(
			'Date'=>'ga:week',
			'Visits'=>'ga:visits',
			'Bounce Rate'=>'ga:avgTimeOnSite',
			'Reservation'=>'ga:goal1Completions'
		), 'Date', 'desc');
		*/
	}
}
?>