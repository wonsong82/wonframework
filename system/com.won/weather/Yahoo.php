<?php
// namespace com\won\web;
class com_won_weather_Yahoo {
	
	private $data;
	private $yweather = 'http://xml.weather.yahoo.com/ns/rss/1.0';
	private $geo = 'http://www.w3.org/2003/01/geo/wgs84_pos#';
	
	public function __construct($woeid){
		
		$url = 'http://weather.yahooapis.com/forecastrss?w=' . $woeid;
		$rss = simplexml_load_file($url);		
		$this->data = $rss;
		
	}
	
	public function getData(){
		return (string)$this->data->channel->item->description;
	}
	
	public function getTemp($unit='f'){
		$s = $this->data->channel->item->children($this->yweather);
		$t = $s->condition->attributes()->temp;
		return $unit=='f'? $t : $this->ftoc($t);		
	}
	
	public function getText(){
		$s = $this->data->channel->item->children($this->yweather);
		return $s->condition->attributes()->text;
	}
	
	private function ctof($c){
		return round($c * (9/5) + 32);		
	}
	
	private function ftoc($f){
		return round(($f - 32) * (5/9));
	}
	
}
?>