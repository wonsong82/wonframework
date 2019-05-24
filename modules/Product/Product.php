<?php
// ModuleNavigationSortOrder=6;
class Product extends WonClass
{
	private $table;
	private $category_table;	
	
	protected function init()
	{
		Won::set(new Permalink());
		
		$this->table = Won::get('DB')->prefix . 'product';
		$this->category_table = Won::get('DB')->prefix . 'product_category';
		
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->category_table}` (
				`id` SERIAL NOT NULL,
				`title` VARCHAR(255) NOT NULL DEFAULT '',
				`url_friendly_title` VARCHAR(255) NOT NULL DEFAULT '',
				`description` LONGTEXT NOT NULL DEFAULT '',
				`images` VARCHAR(255) NOT NULL DEFAULT '',
				`available` INT(1) NOT NULL DEFAULT 1,
				`parent_id` BIGINT NOT NULL DEFAULT -1,
				`sort_order` BIGINT NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE (`url_friendly_title`, `title`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);
		
		
		Won::get('DB')->sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`created_time` DATETIME NOT NULL,
				`modified_time` DATETIME NOT NULL,
				`category_id` BIGINT NOT NULL,
				`item_id` VARCHAR(255) NOT NULL DEFAULT '',
				`title` VARCHAR(255) NOT NULL DEFAULT '',
				`url_friendly_title` VARCHAR(255) NOT NULL DEFAULT '',
				`subtitle` VARCHAR(255) NOT NULL DEFAULT '',
				`description` LONGTEXT NOT NULL DEFAULT '',
				`price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
				`available` INT(1) NOT NULL DEFAULT 1,
				`stock` BIGINT NOT NULL DEFAULT -1,
				`weight` VARCHAR(255) NOT NULL DEFAULT '',
				`images` VARCHAR(255) NOT NULL DEFAULT '',
				`videos` VARCHAR(255) NOT NULL DEFAULT '',
				`sort_order` BIGINT NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE (`url_friendly_title`, `item_id`)			
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die(Won::get('DB')->sql->error);		
	}


// PUBLIC FUNCTIONS ///////////////////////////////////////////////////////////////////////
	
	/**
	 * @name products() 
	 * @desc returns the array of all the products
	 * @param none
	 * @return array
	 */	
	public function products()
	{
		$products = $this->categories();
		
		for ($i=0; $i<count($products); $i++)
			$products[$i]['items'] = $this->items($products[$i]['id']);
		return $products;
	}
	

// CATEGORY RELATED /////////////////////////////////


	/**
	 * @name categories()
	 * @desc Returns array of the categories
	 * @param none
	 * @return array
	 */
	public function categories()
	{
		Won::set(new Images());
		$cats = Won::get('DB')->sql->query("
			SELECT * FROM `{$this->category_table}` ORDER BY `sort_order`
		") or die(Won::get('DB')->sql->error);
		
		$list = array();
		if ($cats->num_rows)
		{	
			while ($cat = $cats->fetch_assoc())
			{
				$imgs = array();
				if ($cat['images'] != '') {
					$imgs = explode(',',$cat['images']);					
				}
				$cat['images'] = $imgs;
				$list[] = $cat;
			}
		}
		return $list;
	}	
	
	/**
	 * @name  category($id)
	 * @desc  Returns array of a category info by ID provided
	 * @param int $id : ID of the category
	 * @return array
	 */
	public function category($id)
	{
		$cat = Won::get('DB')->sql->query("
			SELECT * FROM `{$this->category_table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
		
			
		if ($cat->num_rows) {
						
			$cat = $cat->fetch_assoc();
			$imgs = array();
			if ($cat['images'] != '') {
				$imgs = explode(',',$cat['images']);					
			}
			$cat['images'] = $imgs;
		}
		
		return $cat;		
	}
	
	public function category_by_name($url_friendly_name) {
		
		$id = Won::get('DB')->query("
			SELECT `id` FROM `{$this->category_table}`
			WHERE `url_friendly_title` = '{$url_friendly_name}'
		");
		if ($id->num_rows) {
			$id = $id->fetch_assoc();
			return $this->category($id['id']);
		}
		else {
			return array();
		}
	}
	
	
	/**
	 * @name add_category($title)
	 * @desc Add to category with $title provided
	 * @param string $title : Title of the category
	 * @return boolean
	 */
	public function add_category($title)
	{
		if(!$title)
			return false;
		
		$url_title = Won::get('Permalink')->url_friendly_title($title, $this->category_table);
		$title = trim(Won::get('DB')->sql->real_escape_string($title));				
		
		$numrows = Won::get('DB')->sql->query("
			SELECT COUNT(`id`) FROM `{$this->category_table}`
		") or die(Won::get('DB')->sql->error);
		$numrows = $numrows->fetch_row();	
		$numrows = $numrows[0];
		
		Won::get('DB')->sql->query("
			INSERT INTO `{$this->category_table}`
			SET `title` = '{$title}',
				`url_friendly_title` = '{$url_title}',	
				`sort_order` = '{$numrows}'			
		") or die(Won::get('DB')->sql->error);		
	}
	
	/**
	 * @name remove_cateogory($id)
	 * @desc Remove a category by ID provided
	 * @param int $id : ID of the category
	 * @return void
	 */
	public function remove_category($id)
	{
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->category_table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	/**
	 * @name update_sort_category($ids, $start=0)
	 * @desc In a order in which it was provided, reorganize the order of the items, starting from $start
	 * @param string $ids : List of the ids in order to be replaced to separated by comma
	 * @param int $start : Start index
	 * @return void
	 */
	public function update_sort_category($ids, $start=0)
	{
		$ids = explode(',', $ids);
		$to = count($ids) + $start;
		for ($i=$start; $i<$to; $i++)
		{
			Won::get('DB')->sql->query("
				UPDATE `{$this->category_table}`
				SET `sort_order` = '{$i}'
				WHERE `id` = '{$ids[$i]}'
			") or die(Won::get('DB')->sql->error);
		}
	}
	
	/**
	 * @name update_title_category($id, $title)
	 * @desc Update a single category's title
	 * @param int $id : ID of the category
	 * @param string $title : Title to be updated to 
	 * @return string $url_title
	 */
	public function update_title_category($id, $title)
	{
		$url_title = Won::get('Permalink')->url_friendly_title($title, $this->category_table);
		$title = Won::get('DB')->sql->real_escape_string(trim($title));		
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->category_table}`
			SET `title` = '{$title}',
				`url_friendly_title` = '{$url_title}'
			WHERE `id` = '{$id}'							
		") or die(Won::get('DB')->sql->error);	
		
		return $url_title;	
	}
	
	/**
	 * @name update_description_category($id, $desc)
	 * @desc Update a single category's description
	 * @param int $id : ID of the category
	 * @param string $desc : Description to be updated to 
	 * @return void
	 */
	public function update_description_category($id, $desc)
	{
		$desc = Won::get('DB')->sql->real_escape_string(trim($desc));
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->category_table}`
			SET `description` = '{$desc}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_available_category($id, $value)
	{
		Won::get('DB')->sql->query("
			UPDATE `{$this->category_table}`
			SET `available` = '{$value}'				
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_image_category($catid, $imgid)
	{
		Won::get('DB')->query("
			UPDATE `{$this->category_table}`
			SET `image` = '{$imgid}'
			WHERE `id` = '{$catid}'
		");
	}
	
	public function add_image_category($id, $image_id) {
		
		$cat = $this->category($id);
		$cat['images'][] = $image_id;
		$images = implode(',',$cat['images']);
		Won::get('DB')->query("
			UPDATE `{$this->category_table}`
			SET `images` = '{$images}'				
			WHERE `id` = '{$id}'
		");		
	}
	
	public function update_image_sort_category($id, $ids) {
		
		Won::get('DB')->query("
			UPDATE `{$this->category_table}`
			SET `images` = '{$ids}'				
			WHERE `id` = '{$id}'
		");		
	}
	
	public function remove_image_category($id, $image_id) {
		
		$cat = $this->category($id);
		$imgs = $cat['images'];
		
		foreach ($imgs as $key=>$value) {
			if ($value==$image_id) {
				unset($imgs[$key]);
			}
		}
		
		$imgs = implode(',',$imgs);
		Won::get('DB')->query("
			UPDATE `{$this->category_table}`
			SET `images` = '{$imgs}'				
			WHERE `id` = '{$id}'
		");
	}
	


// Item Related /////////////////////////////////////////////////
	
	/**
	 * @name items($category_id=null)
	 * @desc Returns items of $category_id provided. If none provided, return all items in all categories
	 * @param int $category_id : ID of the category
	 * @return array of the items for the category
	 */
	public function items($category_id=null)
	{
		if (!$category_id)
		{
			// get all
		}
		
		else
		{
			$items = Won::get('DB')->sql->query("
			SELECT  * FROM `{$this->table}`
			WHERE `category_id` = '{$category_id}'
			ORDER BY `sort_order`
					 
		") or die(Won::get('DB')->sql->error);
		
			$output = array();
			if ($items->num_rows)
				while ($item = $items->fetch_assoc())
					$output[] = $item;
			
			return $output;		
		}
	}
	
	/**
	 * @name items_by_title($title)
	 * @desc Returns all items in a category by category title
	 * @param string $title : Category title
	 * @return array
	 */
	public function items_by_title($title)
	{
		$items = Won::get('DB')->sql->query("
			SELECT  `p`.`id` AS `id`,
					`p`.`item_id` AS `item_id`, 
					`p`.`created_time` AS `created_time`,
					`p`.`modified_time` AS `modified_time`,					
					`p`.`title` AS `title`,
					`p`.`url_friendly_title` AS `url_friendly_title`,
					`p`.`subtitle` AS `subtitle`,
					`p`.`description` AS `description`,
					`p`.`price` AS `price`,
					`p`.`available` AS `available`,
					`p`.`stock` AS `stock`,
					`p`.`weight` AS `weight`,
					`p`.`images` AS `images`,
					`p`.`videos` AS `videos`,				
					`p`.`category_id` AS `category_id`,					
					`c`.`title` AS `category_title`,
					`c`.`url_friendly_title` AS `category_url_friendly_title`,
					(SELECT COUNT(*) FROM `{$this->table}`
									WHERE `category_id` = 
									(SELECT `id` FROM `{$this->category_table}`
										WHERE `url_friendly_title` = '{$title}')) AS `total`
			FROM	`{$this->category_table}` c,
					`{$this->table}` p
			WHERE	`p`.`category_id` = `c`.`id` 
			AND		`c`.`url_friendly_title` = '{$title}'
			ORDER BY `p`.`sort_order`
					 
		") or die(Won::get('DB')->sql->error);
		
		$output = array();
		if ($items->num_rows) {
			while ($item = $items->fetch_assoc()) {
				
				$imgs = array();
				if ($item['images'] != '') {
					$imgs = explode(',',$item['images']);					
				}
				$item['images'] = $imgs;
				
				$videos = array();
				if ($item['videos'] != '') {
					$videos = explode(',',$item['videos']);
				}
				$item['videos'] = $videos;
				
				$output[] = $item;
			}
		}
		
		return $output;
	}
	
	/**
	 * @name item($id)
	 * @desc Returns info of an Item (by ID)
	 * @param int $id : ID of the item
	 * @return array
	 */
	public function item($id)
	{
		$items = Won::get('DB')->sql->query("
			SELECT  `p`.`id` AS `id`,
					`p`.`item_id` AS `item_id`, 
					`p`.`created_time` AS `created_time`,
					`p`.`modified_time` AS `modified_time`,					
					`p`.`title` AS `title`,
					`p`.`url_friendly_title` AS `url_friendly_title`,
					`p`.`subtitle` AS `subtitle`,
					`p`.`description` AS `description`,
					`p`.`price` AS `price`,
					`p`.`available` AS `available`,
					`p`.`stock` AS `stock`,
					`p`.`weight` AS `weight`,
					`p`.`images` AS `images`,
					`p`.`videos` AS `videos`,				
					`p`.`category_id` AS `category_id`,					
					`c`.`title` AS `category_title`,
					`c`.`url_friendly_title` AS `category_url_friendly_title`					
			FROM	`{$this->category_table}` c,
					`{$this->table}` p
			WHERE	`p`.`id` = '{$id}' 
			LIMIT 1				 
		") or die(Won::get('DB')->sql->error);
		
		if ($items->num_rows) {
						
			$item = $items->fetch_assoc();
			$imgs = array();
				if ($item['images'] != '') {
					$imgs = explode(',',$item['images']);					
				}
				$item['images'] = $imgs;
				
				$videos = array();
				if ($item['videos'] != '') {
					$videos = explode(',',$item['videos']);
				}
				$item['videos'] = $videos;
			return $item;
		}
		
		return false;	
	}
	
	public function item_by_name($url_friendly_name) {
		
		$id = Won::get('DB')->query("
			SELECT `id` FROM `{$this->table}`
			WHERE `url_friendly_title` = '{$url_friendly_name}'
		");
		if ($id->num_rows) {
			$id = $id->fetch_assoc();
			return $this->item($id['id']);
		}
		else {
			return array();
		}
	}
	
	/**
	 * @name add_item($title, $cat_id)
	 * @desc Add an item into category by id
	 * @param string $title : Name of the title of an item
	 * @param int $cat_id : ID of the category
	 * @return bool
	 */
	public function add_item($title, $cat_id)
	{
		if (!$title)
			return false;		
		
		$url_title = Won::get('Permalink')->url_friendly_title($title, $this->table);
		$title = trim(Won::get('DB')->sql->real_escape_string($title));				
		
		$numrows = Won::get('DB')->sql->query("
			SELECT COUNT(`id`) FROM `{$this->table}`
			WHERE `category_id` = (SELECT `id` FROM `{$this->category_table}`
							WHERE `id` = '{$cat_id}')
		") or die(Won::get('DB')->sql->error);
		$numrows = $numrows->fetch_row();
		$numrows = $numrows[0];
		
		$current_time = date('Y-m-d H:i:s');
				
		Won::get('DB')->sql->query("
			INSERT INTO `{$this->table}`
			SET `created_time` = '{$current_time}',
				`modified_time` = '{$current_time}',
				`title` = '{$title}',
				`url_friendly_title` = '{$url_title}',					
				`sort_order` = '{$numrows}',
				`category_id` = '{$cat_id}'
		") or die(Won::get('DB')->sql->error);	
	}
	
	/**
	 * @name add_item_by_title($title)
	 * @desc Add an item to the table into category by title
	 * @param string $title : Name of the title of an item
	 * @param string $cat : Title of the category
	 * @return bool
	 */
	public function add_item_by_title($title, $cat)
	{
		if (!$title)
			return false;		
		
		$url_title = Won::get('Permalink')->url_friendly_title($title, $this->table);
		$title = trim(Won::get('DB')->sql->real_escape_string($title));				
		
		$numrows = Won::get('DB')->sql->query("
			SELECT COUNT(`id`) FROM `{$this->table}`
			WHERE `category_id` = (SELECT `id` FROM `{$this->category_table}`
							WHERE `title` = '{$cat}')
		") or die(Won::get('DB')->sql->error);
		$numrows = $numrows->fetch_row();
		$numrows = $numrows[0];
		
		$current_time = date('Y-m-d H:i:s');
				
		Won::get('DB')->sql->query("
			INSERT INTO `{$this->table}`
			SET `created_time` = '{$current_time}',
				`modified_time` = '{$current_time}',
				`title` = '{$title}',
				`url_friendly_title` = '{$url_title}',					
				`sort_order` = '{$numrows}',
				`category_id` = (SELECT `id` FROM `{$this->category_table}`
							WHERE `url_friendly_title` = '{$cat}')	
		") or die(Won::get('DB')->sql->error);		
	}
	
	/**
	 * @name remove_item($id)
	 * @desc Removes an item
	 * @param int $id : ID of an item to be removed
	 * @return void
	 */
	public function remove_item($id)
	{
		Won::get('DB')->sql->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
		
	/**
	 * @name update_sort_item($ids, $start=0)
	 * @desc In a order in which it was provided, reorganize the order of the items, starting from $start
	 * @param string $ids : List of the ids in order to be replaced to separated by comma
	 * @param int $start : Start index
	 * @return void
	 */
	public function update_sort_item($ids, $start=0)
	{
		$ids = explode(',', $ids);
		$to = count($ids) + $start;
		for ($i=$start; $i<$to; $i++)
		{
			Won::get('DB')->sql->query("
				UPDATE `{$this->table}`
				SET `sort_order` = '{$i}'
				WHERE `id` = '{$ids[$i]}'
			") or die(Won::get('DB')->sql->error);
		}
	}
	
	/**
	 * @name update_title_item($id, $title)
	 * @desc Updatees the title of an item
	 * @param int $id : ID of an item
	 * @param string $title : Title to be updated to
	 * @return string $url_title
	 */
	public function update_title_item($id, $title)
	{
		$url_title = Won::get('Permalink')->url_friendly_title($title, $this->table);
		$title = Won::get('DB')->sql->real_escape_string(trim($title));
		
		$current_time = date('Y-m-d H:i:s');
		
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `title` = '{$title}',
				`url_friendly_title` = '{$url_title}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'							
		") or die(Won::get('DB')->sql->error);	
		
		return $url_title;	
	}
	
	/**
	 * @name update_subtitle_item($id, $value)
	 * @desc Updates the subtitle of an item
	 * @param int $id : ID of an item
	 * @param string $value : subtitle to be updated to
	 * @return string $url_title
	 */
	public function update_subtitle_item($id, $value)
	{
		$current_time = date('Y-m-d H:i:s');
		$value = Won::get('DB')->sql->real_escape_string(trim($value));		
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `subtitle` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	/**
	 * @name update_description_item($id, $desc)
	 * @desc Updates the description of an item
	 * @param int $id : ID of an item
	 * @param string $desc : description of an item
	 * @return void
	 */
	public function update_description_item($id, $desc)
	{
		$current_time = date('Y-m-d H:i:s');
		$desc = Won::get('DB')->sql->real_escape_string(trim($desc));		
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `description` = '{$desc}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	/**
	 * @name update_itemid_item($id, $value)
	 * @desc Updates the itemid of an item
	 * @param int $id : ID of an item
	 * @param string $value : itemid to be updated to
	 * @return void or false when duplicated item exists
	 */
	public function update_itemid_item($id, $value)
	{
		$value = Won::get('DB')->sql->real_escape_string(strip_tags(trim($value)));
		
		if ($value != '')
		{				
			$dup = Won::get('DB')->sql->query("
				SELECT COUNT(*) FROM `{$this->table}`
				WHERE `item_id` = '{$value}' 
			") or die(Won::get('DB')->sql->error);
			$dup = $dup->fetch_row();
			$dup = $dup[0];
			
			if ($dup > 0)
			{
				$this->error = 'Duplicated Item ID';
				return;
			}
		}
				
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `item_id` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	/**
	 * @name update_price_item($id, $value)
	 * @desc Updates the price of an item
	 * @param int $id : ID of an item
	 * @param string $value : price to be updated to
	 * @return void or false if price is not numeric
	 */
	public function update_price_item($id, $value)
	{
		if (!is_numeric($value))
		{
			$this->error = 'Price must be numeric';
			return false;
		}
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `price` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function update_weight_item($id, $value)
	{
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `weight` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	
	/**
	 * @name update_available_item($id, $value)
	 * @desc Updates the avaiable of an item
	 * @param int $id : ID of an item
	 * @param string $value : available to be updated to
	 * @return void
	 */
	public function update_available_item($id, $value)
	{
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `available` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	/**
	 * @name update_stock_item($id, $value)
	 * @desc Updates the num of stocks of an item
	 * @param int $id : ID of an item
	 * @param string $value : stocks to be updated to
	 * @return void
	 */
	public function update_stock_item($id, $value)
	{
		if (!is_numeric($value))
		{
			$this->error = 'Stock count must be numeric';
			return false;
		}
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->sql->query("
			UPDATE `{$this->table}`
			SET `stock` = '{$value}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		") or die(Won::get('DB')->sql->error);
	}
	
	public function add_image_item($id, $image_id) {
		
		$item = $this->item($id);
		$item['images'][] = $image_id;
		$images = implode(',',$item['images']);
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `images` = '{$images}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");		
	}
	
	public function update_image_sort_item($id, $ids) {
		
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `images` = '{$ids}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");		
	}
	
	public function remove_image_item($id, $image_id) {
		
		$current_time = date('Y-m-d H:i:s');
		$item = $this->item($id);
		$imgs = $item['images'];
		
		foreach ($imgs as $key=>$value) {
			if ($value==$image_id) {
				unset($imgs[$key]);
			}
		}
		
		$imgs = implode(',',$imgs);
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `images` = '{$imgs}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");
	}
	
	public function add_video_item($id, $video_id) {
		
		$item = $this->item($id);
		$item['videos'][] = $video_id;
		$videos = implode(',',$item['videos']);
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `videos` = '{$videos}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");		
	}
	
	public function update_video_sort_item($id, $ids) {
		
		$current_time = date('Y-m-d H:i:s');
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `videos` = '{$ids}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");		
	}
	
	public function remove_video_item($id, $video_id) {
		
		$current_time = date('Y-m-d H:i:s');
		$item = $this->item($id);
		$vds = $item['videos'];
		
		foreach ($vds as $key=>$value) {
			if ($value==$video_id) {
				unset($vds[$key]);
			}
		}
		
		$vds = implode(',',$vds);
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `videos` = '{$vds}',
				`modified_time` = '{$current_time}'
			WHERE `id` = '{$id}'
		");
	}
	
	
}
?>