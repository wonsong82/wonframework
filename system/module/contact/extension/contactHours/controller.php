<?php
class ContactHoursController extends ControllerExtension{
	
	public function getHours($name, $format='start - end',$combine=false,$combineFormat='start~end'){
		if(!isset($this->contact->contacts[$name])){
			$this->contact->setError('INVALID_NAME');
			return false;
		}
		$contact=$this->contact->contacts[$name];
		$days = array('mon','tue','wed','thu','fri','sat','sun');
		foreach($days as $day){
			$s = 'hours'.ucwords($day).'Start';
			$e = 'hours'.ucwords($day).'End';
			if($contact[$s]&&$contact[$e])
				$hours[$day]=str_replace('start',$contact[$s],str_replace('end',$contact[$e],$format));
		}
		if($combine){
			$data=array();
			foreach($hours as $day=>$hour){
				if(count($data)==0) //if mon
					$data[]=array('days'=>array($day),'hour'=>$hour);
				else{ //if not mon
					if($data[count($data)-1]['hour']==$hour)
						$data[count($data)-1]['days'][]=$day;
					else
						$data[]=array('days'=>array($day),'hour'=>$hour);
				}
			}
			$hours=array();
			foreach($data as $d){
				if(count($d['days'])>1){
					$s=$d['days'][0];
					$e=$d['days'][count($d['days'])-1];
					$ds=str_replace('start',$s,str_replace('end',$e,$combineFormat));
					$hours[$ds]=$d['hour'];
				}
				else
					$hours[$d['days'][0]]=$d['hour'];
			}
		}
		return $hours;
	}
	
	public function updateHour($id,$day,$start,$end){
		$id=(int)$id;
		$startRef='contact.'.ucwords($day).'Start';
		$endRef='contact.'.ucwords($day).'End';
		if(!$this->model->validate($startRef,$start)||!$this->model->validate($endRef,$end)){
			$this->setError('INVALID_TIME');
			return false;
		}
		$this->model->update($startRef,$id,$start);
		$this->model->update($endRef,$id,$end);
		return true;
	}
}
?>