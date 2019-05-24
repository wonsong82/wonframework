<?php
// ModuleNavigationSortOrder=5;
class Banner extends WonClass
{
	/**
	 * @name $table
	 * @desc Database table name.
	 * @type string
	 */	
	 private $table;
	
		
	/**
	 * @name initialize_table()
	 * @desc Initialize the table
	 * @param none
	 * @return void
	 */
	public function init() 
	{
		// set Prefix
		$this->table = Won::get('DB')->prefix . 'banner';
		
		// create table		
		Won::get('DB')->sql->query("
		
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`imgpath` VARCHAR(255) NOT NULL,
				`title` VARCHAR(255) NOT NULL,
				`desc` VARCHAR(255) NOT NULL,
				`link` VARCHAR(255) NOT NULL,				
				PRIMARY KEY (`id`)				
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
			
		") or die(Won::get('DB')->sql->error);
	}
	
	
	
	
	
	/**
	 * @name get_banners()
	 * @desc Returns the array of the banner data
	 * @param none
	 * @return array
	 */
	public function get_banners() 
	{		
		$index = 1;
		
		$banners = Won::get('DB')->sql->query("
		
			SELECT * FROM `{$this->table}`
			ORDER BY `id`
			
		") or die(Won::get('DB')->sql->error);
		
		$data = array();				
		if ($banners->num_rows!=0) 
		{
			while ($banner = $banners->fetch_assoc())
			{
				$banner['first'] = $index==1? true:false;
				$banner['last'] = $index==$banners->num_rows? true:false;
				$banner['imgpath'] = Won::get('Config')->content_url . $banner['imgpath'];				
				
				$data[] = $banner;
				
				$index++;
			}
		}		
		
		return $data;
	}	
	
	
	
	
	
	/**
	 * @name update(int $id, string $key, string $value)
	 * @desc Update a banner
	 * @param int $id : id of the row to be modified.
	 * @param string $key : key of the item to be modified.
	 * @param string $value : value of the key of the item to be modified. 
	 * @return void
	 */		
	public function update($id, $key, $value)
	{
		$value = Won::get('DB')->sql->real_escape_string(trim($value));
		
		Won::get('DB')->sql->query("
			
			UPDATE `{$this->table}`
			SET		`{$key}` = '{$value}'
			WHERE `id` = '{$id}'
		
		");		
	}
	
	
	
	
	
	/**
	 * @name add(string $imgpath, string $title, string $desc, string $link)	 
	 * @desc Add a banner to the database.	 
	 * @param string $imgpath : Image path after content - ex : /banners/image.jpg	 
	 * @param string $title : Title of the banner
	 * @param string $desc : Description of the banner
	 * @param string $link : Link to when the banner gets clicked
	 * @return void
	 */	
	public function add($imgpath, $title, $desc, $link)
	{
		$imgpath = Won::get('DB')->sql->real_escape_string(trim($imgpath));
		$title = Won::get('DB')->sql->real_escape_string(trim($title));
		$desc = Won::get('DB')->sql->real_escape_string(trim($desc));
		$link = Won::get('DB')->sql->real_escape_string(trim($link));		
		
		Won::get('DB')->sql->query("
			
			INSERT INTO `{$this->table}` 
			SET `imgpath` = '$imgpath',
				`title` = '$title',
				`desc` = '$desc',
				`link` = '$link'
			
		") or die(Won::get('DB')->sql->error);		
	
	}
	
	
	/**
	 * @name remove(int $id)
	 * @desc Remove a banner from database.
	 * @param int $id : id of the row to be removed.
	 * @return void
	 */	
	public function remove($id)
	{
		Won::get('DB')->sql->query("
			
			DELETE FROM `{$this->table}`
			WHERE `id` = '{$id}'
			
		") or die(Won::get('DB')->sql->error);
	}
	
	
	/**
	 * @name swap(int $id1, int $id2)
	 * @desc Switch $id1 and $id's row position in the database.
	 * @param int $id1 : $id of the banner to be switched from.
	 * @param int $id2 : $id of the banner to be switched to.
	 * @return void
	 */	
	public function swap($id1, $id2)
	{
		// get $temp_id from $last_id + 1
		$last = Won::get('DB')->sql->query("
		
			SELECT `id` FROM `{$this->table}`
			ORDER BY `id` DESC
			LIMIT 1
		
		") or die(Won::get('DB')->sql->error);
		
		$last = $last->fetch_assoc();
		$last_id = $last['id'];
		$temp_id = $last_id + 1;
		
		// update $id1 to $temp_id
		Won::get('DB')->sql->query("
		
			UPDATE `{$this->table}`
			SET `id` = '{$temp_id}'
			WHERE `id` = '{$id1}'
		
		") or die(Won::get('DB')->sql->error);	
		
		// update $id2 to $id1
		Won::get('DB')->sql->query("
		
			UPDATE `{$this->table}`
			SET `id` = '{$id1}'
			WHERE `id` = '{$id2}'
		
		") or die(Won::get('DB')->sql->error);
		
		// update $id1 back to $id2
		Won::get('DB')->sql->query("
		
			UPDATE `{$this->table}`
			SET `id` = '{$id2}'
			WHERE `id` = '{$temp_id}'
		
		") or die(Won::get('DB')->sql->error);
	}
	
}

?>