<?php
// ModuleNavigationSortOrder=7;
class Events extends WonClass {
	
	private $table;
	
	public function init() {
		
		$this->table = $this->DB->prefix.'events';
		
		$this->DB->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`start_date` DATETIME NOT NULL,
				`end_date` DATETIME NOT NULL,
				`title` VARCHAR(255) NOT NULL DEFAULT '',
				`url_friendly_title` VARCHAR(255) NOT NULL DEFAULT '',
				`content` TEXT NOT NULL DEFAULT '',
				`images` VARCHAR(255) NOT NULL DEFAULT '',
				`sort_order` BIGINT NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE (`url_friendly_title`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		");
	}
	

/* GET CONTROLLERS */

	// get all Events
	public function getEvents() {
		$events = $this->DB->query("
			SELECT * FROM `{$this->table}`
			ORDER BY `sort_order` DESC
		");
		$data = array();
		if ($events->num_rows) {
			while ($event = $events->fetch_assoc()) {
				$imgs = array();
				if ($event['images'] != '') {
					$imgs = explode(',',$event['images']);
					$imgs = $this->Images->getImages($imgs);
				}
				$event['images'] = $imgs;
				$event['start_date'] = date('Y-m-d',strtotime($event['start_date']));
			$event['end_date'] = date('Y-m-d',strtotime($event['end_date']));
				$data[] = $event;
			}
		}
		return $data;
	}

	// get Event by $id
	public function getEvent($id) {
		$id = (int)$id;
		$event = $this->DB->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		if ($event->num_rows) {
			$event = $event->fetch_assoc();
			$imgs = array();
			if ($event['images'] != '') {
				$imgs = explode(',',$event['images']);
				$imgs = $this->Images->getImages($imgs);
			}
			$event['images'] = $imgs;
			$event['start_date'] = date('Y-m-d',strtotime($event['start_date']));
			$event['end_date'] = date('Y-m-d',strtotime($event['end_date']));
			return $event;
		}
		return null;
	}
	
	// get Event by title
	public function getEventByName($url_friendly_title) {
		$id = $this->DB->query("
			SELECT `id` FROM '{$this->table}'
			WHERE `url_friendly_title` = '{$url_friendly_title}'
		");
		if ($id->num_rows) {
			$id = $id->fetch_assoc();
			$id = $id['id'];
			$id = (int)$id;
			return $this->getEvent($id);
		}
		return null;
	}
	
	
	
	
/* DB CONTROLLERS */	
	
	// add event , just with the title and others default value
	public function addEvent($title) {
		if (!$title)
			return false;
				
		$url_title = $this->Permalink->url_friendly_title($title, $this->table);
		$title = $this->DB->sql->real_escape_string(trim($title));
		$numrows = $this->DB->query("
			SELECT COUNT(`id`) FROM `{$this->table}`
		");
		$numrows = $numrows->fetch_row();
		$numrows = $numrows[0];
		
		$current_time = date('Y-m-d');
		
		$this->DB->query("
			INSERT INTO `{$this->table}`
			SET `start_date` = '{$current_time}',
				`end_date` = '{$current_time}',
				`title` = '{$title}',
				`url_friendly_title` = '{$url_title}',
				`sort_order` = '{$numrows}'
		");
	}
	
	// remove event
	public function removeEvent($id) {
		$id = (int)$id;
		$this->DB->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = {$id}
		");
	}
	
	// update title
	public function updateTitle($id, $title) {
		$id = (int)$id;
		$urlTitle = $this->Permalink->url_friendly_title($title, $this->table);
		$title = $this->DB->sql->real_escape_string(trim($title));
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `title` = '{$title}',
				`url_friendly_title` = '{$urlTitle}'
			WHERE `id` = {$id}
		");
		return array('title'=>$urlTitle);
	}
	
	// update Dates
	public function updateDates($id, $startDate, $endDate) {
		$id = (int) $id;
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
		if ($startDate > $endDate) 
			return false;
		$startDate = date('Y-m-d', $startDate);
		$endDate = date('Y-m-d', $endDate);
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `start_date` = '{$startDate}',
				`end_date` = '{$endDate}'
			WHERE `id` = {$id}
		");
	}
	
	// update Contents
	public function updateContent($id, $content) {
		$id = (int) $id;
		$content = $this->DB->sql->real_escape_string(trim($content));
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `content` = '{$content}'
			WHERE `id` = {$id}
		");
	}
	
	// add Image
	public function addImage($id, $imagePath, $thumbWidth, $thumbHeight) {
		$size = getimagesize($imagePath);
		$w = (int)$size[0];
		$h = (int)$size[1];
		$thumbWidth = (int)$thumbWidth;
		$thumbHeight = (int)$thumbHeight; 
		$imageid = $this->Images->addImage($imagePath, $w, $h, $thumbWidth, $thumbHeight);		
		
		$id = (int)$id;
		$images = $this->DB->query("
			SELECT `images` FROM {$this->table}
			WHERE `id` = {$id}
		");
		$images = $images->fetch_assoc();
		$images = $images['images'];
		$images = explode(',',$images);
		$images[] = $imageid;
		$images = implode(',',$images);
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `images` = '{$images}'
			WHERE `id` = {$id}
		");
	}
	
	// remove Image
	public function removeImage($id, $imageid) {
		$imageid = (int)$imageid;
		$this->Images->removeImage($imageid);		
		$id = (int)$id;
		$images = $this->DB->query("
			SELECT `images` FROM {$this->table}
			WHERE `id` = {$id}
		");
		$images = $images->fetch_assoc();
		$images = $images['images'];
		$images = explode(',',$images);
		foreach ($images as $key=>$val) {
			if ((int)$val == (int)$imageid) {
				unset($images[$key]);
			}
		}
		$images = implode(',',$images);
		$this->DB->query("
			UPDATE `{$this->table}`
			SET `images` = '{$images}'
			WHERE `id` = {$id}
		");
	}
	
	
	// update sort items
	public function sort($ids, $start=0) {
		$ids = explode(',',$ids);
		$ids = array_reverse($ids);
		$to = count($ids) + $start;
		for ($i=$start; $i<$to; $i++) {
			$id = intval($ids[$i]);
			$this->DB->query("
				UPDATE `{$this->table}`
				SET `sort_order` = {$i}
				WHERE `id` = {$id}
			");
		}
	}
	
	// update sort images
	public function sortImages($id, $imageids) {
		$id = (int)$id;
		$this->DB->query("
		UPDATE `{$this->table}`
		SET `images` = '{$imageids}'
		WHERE `id` = {$id}
		");
	}
	
}
?>