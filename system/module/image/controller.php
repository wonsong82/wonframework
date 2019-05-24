<?php
// namespace app\module;
final class app_module_ImageController extends app_engine_Controller{
	
	private $imgUri = 'images/';
	private $imgUrl;
	private $imgDir;
	
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		
		// Set Dirs
		$this->imgUrl = $this->config->content . $this->imgUri;
		$this->imgDir = $this->config->contentDir . $this->imgUri;
		
		is_dir($this->imgDir)? chmod($this->imgDir, 0777) : mkdir($this->imgDir, 0777);
		
					
	}
	
	//
	// Return A image
	public function getImage($id){
		$id=$this->db->escape((int)$id);
		$result = $this->model->query("
			SELECT 	[image.id] AS [id],
					[image.src] AS [src],
					[image.width] AS [width],
					[image.height] AS [height],
					[image.t_src] AS [t_src],
					[image.t_width] AS [t_width],
					[image.t_height] AS [t_height],					
					[image.o_src] AS [o_src],
					[image.o_width] AS [o_width],
					[image.o_height] AS [o_height]
			FROM [image]
			WHERE [image.id] = {$id}
			ORDER BY [order]
		");		
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Invalid Image ID';
			return false;
		}
		$img = $result[0];
		$img['src'] = $this->imgUrl . $img['src'];
		$img['t_src'] = $this->imgUrl . $img['t_src'];
		$img['o_src'] = $this->imgUrl . $img['o_src'];
		return $img;
	}
	
	public function getImageData($id){
		$id=$this->db->escape((int)$id);
		$result = $this->model->query("
			SELECT 	[imagemeta.rw] AS [rw],
					[imagemeta.rh] AS [rh],
					[imagemeta.sx] AS [sx],
					[imagemeta.sy] AS [sy],
					[imagemeta.ex] AS [ex],
					[imagemeta.ey] AS [ey],
					[imagemeta.t_rw] AS [t_rw],
					[imagemeta.t_rh] AS [t_rh],
					[imagemeta.t_sx] AS [t_sx],
					[imagemeta.t_sy] AS [t_sy],
					[imagemeta.t_ex] AS [t_ex],
					[imagemeta.t_ey] AS [t_ey]
			FROM [imagemeta]
			WHERE [imagemeta.imageid] = {$id}
		");
		if($result===false){
			$this->error = $this->db->lastError();
		}
		if(!count($result)){
			$this->error = 'Invalid Image ID';
			return false;
		}
		
		return $result[0];
	}
	
	
	
	// #Admin Function
	// Get All Images
	public function getImages(){
		$result = $this->model->query("
			SELECT 	[image.id] AS [id],
					[image.t_src] AS [src]
			FROM [image]
			ORDER BY [order]
		");
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		foreach($result as &$img){
			$img['imgID'] = '[img#'.$img['id'].']';
			$img['src'] = '<img src="'.$this->imgUrl.$img['src'].'" />';
		}
		
		return $result;
	}
	
	
	// #Admin Function
	// Add an Image, $imgFile is the Image already uploaded
	// Reads Original File and Add with Default Thumbnail Position
	public function add($imgFile, $width, $height, $thumbWidth, $thumbHeight){
		
		if(!file_exists($imgFile)){
			$this->error = 'Unexisting Image File';
			return false;
		}
		
		if(!$this->model->field('image.src')->validate($imgFile)){
			$this->error = 'Invalid Image File';
			return false;
		}
		
		$width = (int)$width; $height=(int)$height; 
		$thumbWidth=(int)$thumbWidth; $thumbHeight=(int)$thumbHeight;
		
		$info = pathinfo($imgFile);
		$oFile = $info['filename'] . '_o.' . strtolower($info['extension']);
		$tFile = $info['filename'] . '_t.' . strtolower($info['extension']);
		$rFile = $info['filename'] . '.'   . strtolower($info['extension']);
		
		
		// Original
		$img = $this->loader->getClass('graphic.Image');
		$img->load($imgFile);
		$img->save($this->imgDir . $oFile);
		$img->destroy();
				
		$oWidth = (int)$img->width;
		$oHeight = (int)$img->height;	
		
		
		// Resize, Resize and Crop
		$ratio = $width/$height;
		$img = $this->loader->getClass('graphic.Image');		
		$img->load($imgFile);
		if($ratio > $img->ratio){ // Desinated Width is Longer
			$img->resize($width, null);
			$reWidth = $img->width; // Resized With before crop
			$reHeight = $img->height;
			$sX = 0;
			$sY = (int)(($reHeight-$height)*0.5);
			$eX = $width;
			$eY = $sY + $height;
			$img->crop($sX, $sY, $eX, $eY);
		}
		else {
			$img->resize(null, $height);
			$reWidth = $img->width;
			$reHeight = $img->height;
			$sX = (int)(($reWidth-$width)*0.5);
			$sY = 0;
			$eX = $sX + $width;
			$eY = $height;
			$img->crop($sX, $sY, $eX, $eY);
		}
		$img->save($this->imgDir . $rFile);
		$img->destroy();
		
		$width = (int)$img->width;
		$height = (int)$img->height;
				
		// Thumbnail
		$ratio = $thumbWidth/$thumbHeight;
		$img = $this->loader->getClass('graphic.Image');
		$img->load($imgFile);
		if($ratio > $img->ratio){
			$img->resize($thumbWidth, null);
			$treWidth = $img->width; // Thumbnail Resized Width before crop
			$treHeight = $img->height; // Thumbnail Resized Height before crop
			$tsX = 0;
			$tsY = (int)(($treHeight-$thumbHeight)*0.5);
			$teX = $thumbWidth;
			$teY = $tsY + $thumbHeight;			
			$img->crop($tsX, $tsY, $teX, $teY);
		}
		else {
			$img->resize(null, $thumbHeight);
			$treWidth = $img->width; // Thumbnail Resized Width before crop
			$treHeight = $img->height; // Thumbnail Resized Height before crop
			$tsX = (int)(($treWidth-$thumbWidth)*0.5);
			$tsY = 0;
			$teX = $tsX + $thumbWidth;
			$teY = $thumbHeight;			
			$img->crop($tsX, $tsY, $teX, $teY);
		}
		$img->save($this->imgDir . $tFile);
		$img->destroy();
		
		$thumbWidth = (int)$img->width;
		$thumbHeight = (int)$img->height;
		
		// Add to DB
		// Everything should be cleaned and escaped already
		$nextOrder = $this->nextOrder('image');
		$result = $this->model->query("
			INSERT INTO [image]
			SET		[image.o_src] = '{$oFile}',
					[image.o_width] = {$oWidth},
					[image.o_height] = {$oHeight},
					[image.src] = '{$rFile}',
					[image.width] = {$width},
					[image.height] = {$height},
					[image.t_src] = '{$tFile}',
					[image.t_width] = {$thumbWidth},
					[image.t_height] = {$thumbHeight},
					[order] = {$nextOrder}
		");
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		//last inserted Image Id				
		$imgID =  $this->db->insertId();
		
		$result = $this->model->query("
			INSERT INTO [imagemeta]
			SET		[imagemeta.imageid] = {$imgID},
					[imagemeta.rw] = {$reWidth},
					[imagemeta.rh] = {$reHeight},
					[imagemeta.sx] = {$sX},
					[imagemeta.sy] = {$sY},
					[imagemeta.ex] = {$eX},
					[imagemeta.ey] = {$eY},
					[imagemeta.t_rw] = {$treWidth},
					[imagemeta.t_rh] = {$treHeight},
					[imagemeta.t_sx] = {$tsX},
					[imagemeta.t_sy] = {$tsY},
					[imagemeta.t_ex] = {$teX},
					[imagemeta.t_ey] = {$teY}
		");
		if($result===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		return $imgID;
	}
	
	public function updateImage($id, $resizeW, $resizeH, $x, $y, $w, $h){
		$id = (int)$this->db->escape($id);
		$image = $this->getImage($id);
		if($image===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		$rw = (int)$resizeW;
		$rh = (int)$resizeH;
		$w = (int)$w;
		$h = (int)$h;
		$sx = (int)$x;
		$sy = (int)$y;
		$ex = $w + $sx;
		$ey = $h + $sy;
		
		$osrc = str_replace($this->imgUrl, $this->imgDir, $image['o_src']);
		$src = str_replace($this->imgUrl, $this->imgDir, $image['src']);
				
		$img = $this->loader->getClass('graphic.Image');
		$img->load($osrc);
		$img->resize($rw, $wh);
		$img->crop($sx,$sy, $ex,$ey);
		$img->save($src);
		$img->destroy();
		
		$result = $this->model->query("
			UPDATE [image]
			SET [image.width] = {$w},
				[image.height] = {$h}
			WHERE [image.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		$result = $this->model->query("
			UPDATE [imagemeta]
			SET [imagemeta.rw] = {$rw},
				[imagemeta.rh] = {$rh},
				[imagemeta.sx] = {$sx},
				[imagemeta.sy] = {$sy},
				[imagemeta.ex] = {$ex},
				[imagemeta.ey] = {$ey}
			WHERE [imagemeta.imageid] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	public function updateThumb($id, $resizeW, $resizeH, $x, $y, $w, $h){
		$id = (int)$this->db->escape($id);
		$image = $this->getImage($id);
		if($image===false){
			$this->error = $this->db->lastError();
			return false;
		}
		
		$rw = (int)$resizeW;
		$rh = (int)$resizeH;
		$w = (int)$w;
		$h = (int)$h;
		$sx = (int)$x;
		$sy = (int)$y;
		$ex = $w + $sx;
		$ey = $h + $sy;
		
		$osrc = str_replace($this->imgUrl, $this->imgDir, $image['o_src']);
		$src = str_replace($this->imgUrl, $this->imgDir, $image['t_src']);
		
		$img = $this->loader->getClass('graphic.Image');
		$img->load($osrc);
		$img->resize($rw, $wh);
		$img->crop($sx,$sy, $ex,$ey);
		$img->save($src);
		$img->destroy();
		
		$result = $this->model->query("
			UPDATE [image]
			SET [image.t_width] = {$w},
				[image.t_height] = {$h}
			WHERE [image.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		$result = $this->model->query("
			UPDATE [imagemeta]
			SET [imagemeta.t_rw] = {$rw},
				[imagemeta.t_rh] = {$rh},
				[imagemeta.t_sx] = {$sx},
				[imagemeta.t_sy] = {$sy},
				[imagemeta.t_ex] = {$ex},
				[imagemeta.t_ey] = {$ey}
			WHERE [imagemeta.imageid] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	//
	// Remove Image
	public function remove($id){
		$id = (int)$this->db->escape($id);
		$image = $this->getImage($id);
		if($image===false){			
			$this->error = $this->db->lastError();
			return false;
		}
		
		
		$result = $this->model->query("
			DELETE FROM [image]
			WHERE [image.id] = {$id}
		");
		$result = $this->model->query("
			DELETE FROM [imagemeta]
			WHERE [imagemeta.imageid] = {$id}
		");
		
		unlink(str_replace($this->imgUrl, $this->imgDir, $image['src']));
		unlink(str_replace($this->imgUrl, $this->imgDir, $image['t_src']));
		unlink(str_replace($this->imgUrl, $this->imgDir, $image['o_src']));
		
		return true;
	}
	
	
	
}
?>