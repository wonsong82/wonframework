<?php
class Image
{
	/**
	 * @name $table
	 * @desc Database table name.
	 * @type string
	 */	
	private $table;
	
	/**
	 * @name $content_folder
	 * @desc Folder path of where images are relative to CONTENT_DIR
	 * @type string
	 */
	private $content_folder = 'images';
	
	/**
	 * @name $img_size
	 * @desc Sets or Gets Image Default Size. Set both width and height for explicit sizes, Set only one for keeping the proportion.
	 * @type array (int)width, (int)height
	 */
	public $img_size = array('width'=>800);
	
	/**
	 * @name $thumb_size
	 * @desc Sets or Gets Thumbnail Default Size. Set both width and height for explicit sizes, Set only one for keeping the proportion.
	 * @type array (int)width, (int)height
	 */
	public $thumb_size = array('width'=>50, 'height'=>50);
		
	/**
	 * @name Image()
	 * @desc Initialize Image class.
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
		// read config if not loaded
		if (!defined('CONFIG_LOADED'))
			require_once '../../config.php';
		
		// set the tablename and initialize table	
		$this->table = Sql::prefix() . 'image';
		$this->init_table();
	}

	
// PRIVATE FUNCTIONS ///////////////////////////////////////////////////////////////
	
	/**
	 * @name init_table()
	 * @desc initialize table.
	 * @param none
	 * return void
	 */
	private function init_table()
	{
		$sql = Sql::sql();
		$sql->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`created_time` DATETIME NOT NULL,
				`modified_time` DATETIME NOT NULL,
				`table` VARCHAR(255) NOT NULL,
				`row_id` BIGINT NOT NULL,
				`img` VARCHAR(255) NOT NULL,
				`img_w` INT(10) NOT NULL,
				`img_h` INT(10) NOT NULL,
				`img_x` INT(10) NOT NULL,
				`img_y` INT(10) NOT NULL,
				`img_original` VARCHAR(255) NOT NULL,
				`img_original_w` INT(10) NOT NULL,
				`img_original_h` INT(10) NOT NULL,								
				`img_thumb` VARCHAR(255) NOT NULL,
				`img_thumb_w` INT(10) NOT NULL,
				`img_thumb_h` INT(10) NOT NULL,
				`img_thumb_x` INT(10) NOT NULL,
				`img_thumb_y` INT(10) NOT NULL
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`
		") or die($sql->error);
	}


// PUBLIC FUNCTIONS ////////////////////////////////////////////////////////////////////
	public function list_images($table, $id)
	{
		$sql = Sql::sql();
		$imgs = $sql->query("
			SELECT * FROM `{$this->table}`
			WHERE `table` = '{$table}' AND `row_id` = '{$id}'
		") or die($sql->error);
		
		if (!$imgs->num_rows)
			return false;
		
		$out = array();
		while ($img = $imgs->fetch_assoc())
		{
			$img['path'] = CONTENT_URL . '/' . $this->content_folder;	
			$img['path_dir'] = CONTENT_DIR . '/' . $this->content_folder;				
			$out[] = $img;
		}
		
		return $out;
	}
	
	
	public function add($file, $table, $id)
	{
		// Image Jpeg
		$original = new Jpeg();
		
		// Upload the Jpeg
		$original->upload($file, CONTENT_DIR.'/'.$this->content_folder);		
		if ($original->error)
			return false;		
		
		// rename original file
		$original_name = preg_replace('#(^.+?)(\.[a-zA-Z]+?)$#', '$1_original$2' , $original->name);
		$original->rename($original_name);
		if ($original->error)
			return false;
		
		
		// create resize Image Jpeg
		$resized_file_path = preg_replace('#(^.+?)_original(\.[a-zA-Z]+?)$#', '$1$2' , $original->path);
		$resize = $original->modify($this->img_size);
		$resize->save($resized_file_path);
		
		
		// create thumb nail
		$thumb_path = preg_replace('#(^.+?)_original(\.[a-zA-Z]+?)$#', '$1_thumb$2' , $original->path);
		$thumb = $original->modify($this->thumb_size);
		$thumb->save($thumb_path);		
				
		// Add to database
		$sql = Sql::sql();		
		$current_time = date('Y-m-d H:i:s');
		
		$sql->query("
			INSERT INTO `{$this->table}`
			SET `created_time` = '{$current_time}',
				`modified_time` = '{$current_time}',
				`table` = '{$table}',
				`row_id` = '{$id}',
				`img` = '{$resize->name}',
				`img_w` = '{$resize->width}',
				`img_h` = '{$resize->height}',
				`img_x` = '{$resize->offset_x}',
				`img_y` = '{$resize->offset_y}',
				`img_original` = '{$original->name}',
				`img_original_w` = '{$original->width}',
				`img_original_h` = '{$original->height}',				
				`img_thumb` = '{$thumb->name}',
				`img_thumb_w` = '{$thumb->width}',
				`img_thumb_h` = '{$thumb->height}',
				`img_thumb_x` = '{$thumb->offset_x}',
				`img_thumb_y` = '{$thumb->offset_y}'
		") or die($sql->error);
		
		return true;	
	}
	
	public function remove($id)
	{
		$sql->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = '$id'
		") or die($sql->error);
	}
	
	
	public function modify_image($id, $size=array(), $offset=array())
	{
		$sql = Sql::sql();
		// find the original image file name
		$result = $sql->query("
			SELECT `img_original`
			FROM `{$this->table}`
			WHERE `id` = '$id'
		") or die($sql->error);
		
		// if not found
		if (!$result->num_rows)
			return false;
		
		// if found	
		$orig_img = $result->fetch_assoc();
		$orig_img = $orig_img['img_original'];
		$original = new File();
		$original->load(CONTENT_DIR . '/' . $this->content_folder . '/' . $orig_img); 
		
		$resized_file_path = preg_replace('#(^.+?)_original(\.[a-zA-Z]+?)$#', '$1$2' , $original->path);
		$resized = Image::jpeg_resize($original->path, $resized_file_path, $size, $offset);
		
		return true;
	}
	
	public function modify_thumbnail()
	{
	}
	
	public function swap()
	{
	}

	
	
	
	
		

	
	
		
}
?>

