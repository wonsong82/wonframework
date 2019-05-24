<?php
// ModuleNavigationSortOrder=4;
class Contents extends WonClass
{	
	private $pageTable; //page table {id, permalink}
	private $elementTable; //element table {id, page_id, type, value, parent_id, sort_order}
	public $settings;
		
	protected function init() {
		// Define Tables
		$this->pageTable = Won::get('DB')->prefix . 'contents_page';
		$this->elementTable = Won::get('DB')->prefix . 'contents_element';
		
		// Create Tables
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->pageTable}` (
				`id` SERIAL NOT NULL,
				`uri` VARCHAR(255) NOT NULL DEFAULT '',
				PRIMARY KEY (`id`),
				UNIQUE (`uri`)				
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);
		
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->elementTable}` (
				`id` SERIAL NOT NULL,
				`page_id` BIGINT NOT NULL,
				`title` VARCHAR(255) NOT NULL DEFAULT '',
				`type` VARCHAR(255) NOT NULL,
				`value` LONGTEXT NOT NULL DEFAULT '',
				`parent_id` BIGINT NOT NULL,
				`sort_order` BIGINT NOT NULL,
				PRIMARY KEY (`id`)				
			) ENGINE =INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);	
		
		// load settings
		Won::set(new Settings());
		$settings = Won::get('Settings')->getSettings($this);		
		
		if (!$settings) {		
			$defaultImageSize = array('width'=>'','height'=>'');
			$defaultThumbSize = array('width'=>'','height'=>'');
			Won::get('Settings')->setSetting($this, 'imgSize', $defaultImageSize);
			Won::get('Settings')->setSetting($this, 'thumbSize', $defaultThumbSize);
			$settings = Won::get('Settings')->getSettings($this);
		}
		
		// load settings
		$this->settings['imgSize'] =  $settings['imgSize']['width']&&$settings['imgSize']['height']? 
			$settings['imgSize'] : Won::set(new Images())->imgSize;
		
		$this->settings['thumbSize'] =  $settings['thumbSize']['width']&&$settings['thumbSize']['height']? 
			$settings['thumbSize'] : Won::set(new Images())->thumbSize;	
					
	}
	
	
// PAGE RELATED FUNCTIONS ///////////////////////////////////////////////////////////////
	
	// get all pages
	public function get_pages() {
		$pages = Won::get('DB')->sql->query("
			SELECT `id`, `uri` 
			FROM `{$this->pageTable}`
			ORDER BY `id`
		") or die(Won::get('DB')->sql->error);
		
		$data = array();
		if ($pages->num_rows) {
			while ($page = $pages->fetch_assoc())
				$data[] = $page;
		}
		return $data;
	}	
	
	// Add a new page
	public function add_page($uri) {		
		$uri = Won::get('DB')->sql->real_escape_string(trim(trim($uri),'/'));
		
		if ($uri == '')
			return 'Need an uri';
		
		if (preg_match('/^https?|www\./i', $uri))
			return 'Can only be uri within scoupe of this site';
				
		Won::get('DB')->sql->query("
			INSERT INTO `{$this->pageTable}` 
			SET `uri` = '$uri' 
		") or die(Won::get('DB')->sql->error);
	}
	
	// Get Page
	public function get_page($page_id) {
		$page = Won::get('DB')->sql->query("
			SELECT * FROM `{$this->pageTable}`
			WHERE `id` = {$page_id}
		") or die(Won::get('DB')->sql->error);
		
		if ($page->num_rows) {
			return $page->fetch_assoc();			
		}		
	}
	
		
	// Remove a page 
	public function remove_page($id) {
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->pageTable}`
			WHERE `id` = {$id}
		") or die(Won::get('DB')->sql->error);
	}
	
	// Get an element
	public function get_element($element_id) {
		$element = Won::get('DB')->sql->query("
			SELECT * FROM `{$this->elementTable}`
			WHERE `id` = {$element_id}
		") or die(Won::get('DB')->sql->error);
		$data = array();
		if ($element->num_rows)
			$data = $element->fetch_assoc();
		return $data;
	}
	
	// Add an element to ID
	public function add_element($type, $page_id) {			
		
		$sort = Won::get('DB')->sql->query("
			SELECT `id` FROM `{$this->elementTable}`
			WHERE `page_id` = {$page_id}
			AND `parent_id` = 0
		") or die(Won::get('DB')->sql->error);
		$sort_id = $sort->num_rows;		
		$title = 'New ' . $type . ' element';
		Won::get('DB')->sql->query("
			INSERT INTO `{$this->elementTable}`
			SET `page_id` = {$page_id},
				`title` = '{$title}',
				`type` = '{$type}',
				`value` = '{$value}',
				`parent_id` = 0,
				`sort_order` = {$sort_id}
		") or die(Won::get('DB')->sql->error);
	}
	
	// Remove an element
	public function remove_element($id) {
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->elementTable}`
			WHERE `id` = {$id}
			OR `parent_id` = {$id}
		") or die(Won::get('DB')->sql->error);
	}
	
	// Get all elements by ID
	public function get_elements($page_id) {
		$elements = Won::get('DB')->sql->query("
			SELECT `id`, `type`, `title`, `value`, `parent_id`
			FROM `{$this->elementTable}`
			WHERE `page_id` = $page_id
			ORDER BY `sort_order`
		") or die(Won::get('DB')->sql->error);
		
		$data = array();
		$parents = array();
		$childrens = array();
		
		if ($elements->num_rows) {
			while ($element = $elements->fetch_assoc())	{
				
				// if image
				if ($element['type'] == 'image') {
					Won::set(new Images());
					$img = Won::get('Images')->getImage($element['value']);
					$element['value'] = $img;
				}
				// if link
				else if ($element['type'] == 'link') {
					if ($element['value'] != '') {
						$element['value'] = unserialize($element['value']);
					}else {
						$element['value'] = array('text'=>'','href'=>'');
					}
				}
				// if text 
				else if ($element['type'] == 'text') {
					$element['value'] = nl2br($element['value']);
				}
				
				if ($element['parent_id'] == 0)
					$parents[] = $element;
				else {
					$childrens[$element['parent_id']][] = $element;					 
				}
			}
		}
		
		foreach ($parents as $parent) {
			if ($parent['type']=='group') {
				$parent['elements'] = array();
				if (isset($childrens[$parent['id']]))
					$parent['elements'] = $childrens[$parent['id']];			
			}
			$data[] = $parent;
		}		
			
		return $data;
	}
	
	
	public function get_elements_by_uri($uri) {
		$id = Won::get('DB')->query("
			SELECT `id` FROM `{$this->pageTable}`
			WHERE `uri` = '$uri'
		");
		if ($id->num_rows) 
		{
			$id = $id->fetch_assoc();
			$id = $id['id'];
			
			$data = $this->get_elements($id);
			
			return $data;
		}		
	}
	
	
	
	
	// Update element sorts
	public function update_element_sort($ids, $start=0)	{
		$ids = explode(',', $ids);
		$to = count($ids) + $start;
		for ($i=$start; $i<$to; $i++){
			Won::get('DB')->sql->query("
				UPDATE `{$this->elementTable}`
				SET `sort_order` = '{$i}'
				WHERE `id` = '{$ids[$i]}'
			") or die(Won::get('DB')->sql->error);
		}
	}
	
	// Update element parent
	public function update_element_parent($element_id, $parent_id) {
		
		$element = $this->get_element($element_id);
		$page_id = $element['page_id'];
		
		$sort = Won::get('DB')->sql->query("
			SELECT `id` FROM `{$this->elementTable}`
			WHERE `page_id` = {$page_id}
			AND `parent_id` = {$parent_id}
		") or die(Won::get('DB')->sql->error);
		$sort_id = $sort->num_rows;
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->elementTable}`
			SET `parent_id` = {$parent_id},
				`sort_order` = {$sort_id}
			WHERE `page_id` = {$page_id}
			AND `id` = {$element_id}
		") or die(Won::get('DB')->sql->error);
		
	}
	
	// update element title
	public function update_element_title($element_id, $title) {
		
		$title = Won::get('DB')->sql->real_escape_string(strip_tags(trim($title)));
		Won::get('DB')->query("
			UPDATE `{$this->elementTable}`
			SET `title` = '{$title}'
			WHERE `id` = {$element_id}
		");
	}	
	
	// update element value
	public function update_element_value($element_id, $value) {
		
		$value = Won::get('DB')->sql->real_escape_string(trim($value));
		Won::get('DB')->query("
			UPDATE `{$this->elementTable}`
			SET `value` = '{$value}'
			WHERE `id` = {$element_id}		
		");
	}
	
	public function update_link($element_id, $text, $href) {
		
		$value = serialize(array('text'=>$text, 'href'=>$href));
		$this->update_element_value($element_id, $value);
	}
	
}

















?>