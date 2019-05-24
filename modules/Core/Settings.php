<?php
// ModuleNavigationSortOrder=1;
class Settings extends WonClass {
	
	public $table;
	
	protected function init() {				
		$this->table = Won::get('DB')->prefix . 'settings';
				
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`option` VARCHAR(255),
				`value` TEXT,
				UNIQUE (`option`)				
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);			
	}
	
	public function setSetting($className, $option, $value) {
		
		$className = gettype($className)=='string'? $className . '_' : get_class($className) . '_';
		$option = $className . $option;
		$value = serialize($value);
		Won::get('DB')->sql->query("
			REPLACE INTO `{$this->table}`
			SET `option` = '{$option}',
				`value` = '{$value}'
		") or die(Won::get('DB')->sql->error);
		
	}	
	
	public function getSetting($className, $option) {
		
		$className = gettype($className)=='string'? $className . '_' : get_class($className) . '_';
		$option = $className . $option;
		$value = Won::get('DB')->sql->query("
			SELECT `value` FROM `{$this->table}`
			WHERE `option` = '{$option}'
		") or die(Won::get('DB')->sql->error);
		
		$data = false;
		if ($value->num_rows) {
			$value = $value->fetch_assoc();
			$data = unserialize($value['value']);
		}
		
		return $data;
	}
	
	public function getSettings($className) {
		
		$search = gettype($className)=='string' ? $className . '_' : get_class($className) . '_';
		
		$settings = Won::get('DB')->sql->query("
			SELECT `option`,`value` FROM `{$this->table}`
			WHERE `option` LIKE '{$search}%'
		") or die(Won::get('DB')->sql->error);
		$data = false;
		if ($settings->num_rows) {
			$data = array();
			while ($setting = $settings->fetch_assoc())
				$data[substr($setting['option'],strlen($search))] = unserialize($setting['value']);
		}
		return $data;				
	}
}
?>