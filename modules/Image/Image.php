<?php
class Image
{	
	public static function jpeg_resize($image_from, $image_to, $width, $height)
	{
		// instanciate src image and new image
		$src = new JpegImage();
		$src->read($image_from);
		
		$new = new JpegImage();
		$new->create($width, $height);
		
		
		// get resized width and height
		$resized_w;
		$resized_h;
		if ($src->ratio > $new->ratio)
		{
			$resized_h = (int)round($height);
			$resized_w = (int)round($src->width * $resized_h / $src->height);
		}		
		else 
		{
			$resized_w = (int)round($width);
			$resized_h = (int)round($src->height * $resized_w / $src->width);
		}
		
		
		$x = (int) round( ($resized_w - $width) / 2) * -1;
		$y = (int) round( ($resized_h - $height) / 2) * -1;
		
		
		imagecopyresampled(
			$new->image,
			$src->image,
			$x, // dest x
			$y, // dest y
			0, // src x
			0, // src y
			$resized_w, 
			$resized_h,
			$src->width,
			$src->height
		);
		
		imagejpeg($new->image, $image_to); // save
		
		$src = null;
		$new = null;
		
		
		/*
		
		
		
		imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height); // resample the image
		
		imagejpeg($new_image, $image_to); // save into jpeg
		
		imagedestroy($source_image);
		imagedestroy($new_image);
		
		return $image_to;*/
	}	
	
	
	
		
}

class JpegImage
{
	public $image;	
	public $width;
	public $height;
	public $ratio;	
		
	public function create($width, $height)
	{
		$img = imagecreatetruecolor($width, $height);
		
		$this->image = $img;
		$this->width = $width;
		$this->height = $height;
		$this->ratio = $width / $height;		
	}
	
	public function read($img_file_path)
	{
		$img = imagecreatefromjpeg($img_file_path);
		$size = getimagesize($img_file_path);
		
		$this->image = $img;
		$this->width = $size[0];
		$this->height = $size[1];
		$this->ratio = $size[0] / $size[1];			
	}
}


?>