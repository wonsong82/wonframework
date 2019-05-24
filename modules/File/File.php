<?php

class File
{
	public $error;
	public $name;
	public $path;	
	public $type;
	public $size;
	
	public function __construct()
	{
	}
	
		
	
	// Upload the file from $_FILE to uploaded directory
	// $file:Array = $_FILE array
	// $uploaded_dir:String = where to save it to
	public function upload($file, $upload_dir)
	{
		// check for errors
		if (!isset($file['error']) || !isset($file['name']) || !isset($file['type']) || !isset($file['size'])) 
		{
			$this->error = 'Upload Error : Invalid $_FILE';
			return false;
		}
		
		if ($file['error'] > 0)
		{
			$this->error = 'Upload Error : ' . $file['error'];
			return false;
		}
		
		// if good
		
		// if same file name already exists
		$upload_to = rtrim($upload_dir, '/') . '/' . $file['name'];
		$i = 1;
		
		$n = $file['name'];
		
		while (file_exists($upload_to))
		{
			$n_arr = explode('.', $n);
			
			// if the file contains extension
			if (count($n_arr) >= 2)
			{
				$name = $n_arr[count($n_arr)-2];
				
				if (preg_match('#\-[0-9]+$#', $name))
				{
					$oldname = implode('.', $n_arr);
					
					$n_arr[count($n_arr)-2] = preg_replace('#\-[0-9]+$#', '-'.$i, $name);
					$n = implode('.', $n_arr);
					
					$upload_to = str_replace($oldname, $n, $upload_to);					
				}
				else
				{						
					$oldname = implode('.', $n_arr);
													
					$n_arr[count($n_arr)-2] .= '-'.$i;
					$n = implode('.', $n_arr);
					
					$upload_to = str_replace($oldname, $n, $upload_to);
				}
			}
			
			// if file doesnt have extension
			else
			{
				// to be worked on				
			}
			
			$i++;
		}
				
		
		
		// if uploade failed				
		if (!move_uploaded_file($file['tmp_name'], $upload_to))
		{			
			$this->error = 'Upload Error : Upload failed.';
			return false;
		}
		
		
		// if upload is good
		$this->error = false;
		
		$this->name = $n;
		$this->path = $upload_to;
		$this->type = $file['type'];
		$this->size = $file['size'];
		
		return true;		
	}	
		
}

?>