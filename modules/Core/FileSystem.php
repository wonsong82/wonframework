<?php
class FileSystem
{
	
	public function __construct() {}
	
	public function read_directory($directory)
	{
		if (!is_dir($directory))
			return array();
		
		$files = array();
		$dh = opendir($directory);
		while (false !== ($f = readdir($dh)))		
			$files[] = $f;
		
		return $files;
	}
	
	// read directories and its contents
	public function read_directories($directories)
	{
		if (!is_array($directories))
			return array();
		
		$return;
		foreach ($directories as $directory)
		{
			if (is_dir($directory))
			{
				$dh = opendir($directory);

				while (false !== ($file = readdir($dh)))			
					if ($file!='.' && $file!='..')				
						$return[] = $directory . '/' . $file;
			}
		}
		
		return $return;
	}
	
	// Read all Directory, Sub Directories and Their Files.
	public function read_all_files($directory)
	{
		$dirs = array($directory);
		$files = array();
		
		while (count($dirs) > 0)
		{
			$newdirs = array();
			$curdir = $this->read_directories($dirs);
			if (count($curdir))
			{
				foreach ($curdir as $file)
				{
					if (is_dir($file))
						$newdirs[] = $file;
					else
						$files[] = $file;
				}
			}
			$dirs = $newdirs;
		}
		
		return $files;
	}
	
	public function search_string_from_file($find, $file)
	{
		return 
			preg_match($find, file_get_contents($file))
			? true : false;
	}
	
	public function search_string_from_files($find, $files)
	{
		$found = array();
	
		foreach ($files as $file)
		{
			if ($this->search_string_from_file($find, $file))
				$found[] = $file;			
		}
		
		return $found;
	}
	
}
?>