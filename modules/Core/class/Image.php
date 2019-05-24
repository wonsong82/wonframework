<?php
// Representative of a single image
class Image {
	
	private $img;
	public $width;
	public $height;
	public $ratio;
	public $type;
	
	public function __construct($file) {
		
		if (!file_exists($file)) 
			die(__class__.': given file does not exists.');	
		
		$mimeType = preg_replace('/^.*\.(.+?)$/', '$1', basename($file));
		
		// create an image instance
		if ($mimeType == 'jpg')
			$image = imagecreatefromjpeg($file);
		
		elseif ($mimeType == 'png')
			$image = imagecreatefrompng($file);
		
		elseif ($mimeType == 'gif')
			$image = imagecreatefromgif($file);
		
		else
			die(__class__.': only jpeg, png, gif are supported.');
		
		// set the image object to the class
		$this->img = $image;
		$size = getimagesize($file);
		$this->width = $size[0];
		$this->height = $size[1];
		$this->ratio = $size[0]/$size[1];
		$this->type = $mimeType;	
	}
	
	public function resize($size) {
		
		// validate values
		if ((!isset($size['w']) && !isset($size['h'])) ||
			(isset($size['w']) && !isset($size['h']) && !is_numeric($size['w'])) ||
			(isset($size['w']) && isset($size['h']) && (!is_numeric($size['w']) || !is_numeric($size['h'])))) {
			die(__class__.': wrong values given to resize.');
		}
		
		// get width and height
		$w; $h; $r=$this->ratio; $srcw = $this->width; $srch = $this->height;
		if (isset($size['w']) && !isset($size['h'])) { // only width is given
			$w = intval($size['w']);
			$h = intval($size['w']/$r);
		}
		
		elseif (!isset($size['w']) && isset($size['h'])) { // only height is given
			$w = intval($size['h']*$r);
			$h = intval($size['h']);
		}
		
		else { // both given
			$w = intval($size['w']);
			$h = intval($size['h']);
		}
		
		// resize
		$src = $this->img;
		$dst = imagecreatetruecolor($w, $h);				
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $srcw, $srch);
		
		// return a new instance
		$image = clone $this;
		$image->width = $w;
		$image->height = $h;
		$image->ratio = $w/$h;
		$image->img = $dst;
		
		return $image;
	}	
	
	public function crop($p1, $p2) {
		
		// validate values
		if (!isset($p1['x']) || !is_numeric($p1['x']) || // p1['x'] missing or wrong
			!isset($p1['y']) || !is_numeric($p1['y']) || // p1['y'] missing or wrong
			!isset($p2['x']) || !is_numeric($p2['x']) || // p2['x'] missing or wrong
			!isset($p2['y']) || !is_numeric($p2['y'])) { // p2['y'] missing or wrong		
			die(__class__.': wrong values given to crop.');
		}		
				
		
		$x1 = intval($p1['x']);
		$y1 = intval($p1['y']);
		$x2 = intval($p2['x']);
		$y2 = intval($p2['y']);
		$w1 = intval($p2['x']-$p1['x']);
		$h1 = intval($p2['y']-$p1['y']);
		$w2 = $x2-$x1;
		$h2 = $y2-$y1;
				
		// crop
		$src = $this->img;
		$dst = imagecreatetruecolor($w1, $h1);
		imagecopyresampled($dst, $src, 0, 0, $x1, $y1, $w1, $h1, $w2, $h2);	
		
		// return the new instance
		$image = clone $this;
		$image->width = $w1;
		$image->height = $h1;
		$image->ratio = $w1/$h1;
		$image->img = $dst;
				
		return $image; 	
	}
	
	public function save($destination) {
		
		$img = $this->img;
		$type = $this->type;
		
		if ($type == 'jpg') {
			$f = imagejpeg($img, $destination, 90);
		}
		
		elseif ($type == 'png') {
			$f = imagepng($img, $destination);
		}
		
		elseif ($type == 'gif') {
			$f = imagegif($img, $destination);
		}
		
		if (!$f)
			die(__class__.': couldnot save the image to '. $destination);		
	}	
	
	
		
}

?>
