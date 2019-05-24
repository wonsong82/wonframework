<?php
class Videos extends WonClass {
	private $table; // this table
	
	// dynamic data
	public $contentFolder = 'videos';
	
	protected function init() {
		$this->table = Won::get('DB')->prefix . 'videos';
		$query = Won::get('DB')->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`title` VARCHAR(255) NOT NULL DEFAULT '',
				`video` VARCHAR(255) NOT NULL DEFAULT '',
				`thumb` VARCHAR(255) NOT NULL DEFAULT '',
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`	
		");
	}	
	
	public function getVideo($id) {
		
		$id = intval($id);
		$video = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		
		$data = false;
		if ($video->num_rows) {
			$video = $video->fetch_assoc();
			$data = array();
			
			$data['id'] = $video['id'];
			$data['title'] = $video['title'];
			$data['video'] = Won::get('Config')->content_url.'/'.$this->contentFolder.'/'.$video['video'];
			$data['thumb'] = Won::get('Config')->content_url.'/'.$this->contentFolder.'/'.$video['thumb'];
		}
		return $data;
	}
	
	public function getVideos($ids) {
		
		$data = array();
		if (gettype($ids)=='array' && count($ids)>0) {
			foreach ($ids as $id) {
				$video = $this->getVideo($id);
				if ($video) {
					$data[] = $video;
				}
			}
		}
		return $data;
	}
	
	public function addVideo($title, $videoFile, $thumbFile) {
		
		$folder = Won::get('Config')->content_dir.'/'.$this->contentFolder;
		$video = basename($videoFile);
		$thumb = basename($thumbFile);
		$title = Won::get('DB')->sql->real_escape_string(trim($title));
				
		Won::get('DB')->query("
			INSERT INTO `{$this->table}`
			SET `title` = '{$title}',
				`video` = '{$video}',
				`thumb` = '{$thumb}'
		");
		return Won::get('DB')->sql->insert_id;
	}
	
	public function removeVideo($id) {
		
		Won::get('DB')->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = {$id}
		");
	}
	
	
}






















?>