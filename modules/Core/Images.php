<?php
require_once 'class/Image.php';

class Images extends WonClass {
	private $table; // this table
	
	// dynamic data
	public $contentFolder = 'images';
	public $imgSize ;
	public $thumbSize;
	
	protected function init() {
		$this->table = Won::get('DB')->prefix . 'images';
		$query = Won::get('DB')->query("
			CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` SERIAL NOT NULL,
				`original` VARCHAR(255) NOT NULL,
				`original_w` INT(10) NOT NULL,
				`original_h` INT(10) NOT NULL,
				`img` VARCHAR(255) NOT NULL,
				`img_w` INT(10) NOT NULL,
				`img_h` INT(10) NOT NULL,
				`img_x1` INT(10) NOT NULL,
				`img_y1` INT(10) NOT NULL,
				`img_x2` INT(10) NOT NULL,
				`img_y2` INT(10) NOT NULL,
				`thumb` VARCHAR(255) NOT NULL,
				`thumb_w` INT(10) NOT NULL,
				`thumb_h` INT(10) NOT NULL,
				`thumb_x1` INT(10) NOT NULL,
				`thumb_y1` INT(10) NOT NULL,
				`thumb_x2` INT(10) NOT NULL,
				`thumb_y2` INT(10) NOT NULL,
				PRIMARY KEY(`id`)
			) ENGINE = INNODB CHARSET `utf8` COLLATE `utf8_general_ci`	
		");
		
			
		Won::set(new Settings());
		
		// load settings
		$settings = Won::get('Settings')->getSettings($this);		
		if ($settings) {		
			foreach ($settings as $option=>$value) {
				$this->$option = $value;
			}
		}
		
		// if first time, set default values
		else {
			$defaultImageSize = array('width'=>800,'height'=>'600');
			$defaultThumbSize = array('width'=>50,'height'=>50);
			Won::get('Settings')->setSetting($this, 'imgSize', $defaultImageSize);
			Won::get('Settings')->setSetting($this, 'thumbSize', $defaultThumbSize);
			$this->imgSize = $defaultImageSize;
			$this->thumbSize = $defaultThumbSize;
		}		
	}	
	
	public function getImage($id) {
		
		$id = intval($id);
		
		$image = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
				
		$data = false;
		if ($image->num_rows) {
			$image = $image->fetch_assoc();
			$data = array();
			
			$data['id'] = $image['id'];
			$data['img'] = Won::get('Config')->content_url.'/'.$this->contentFolder.'/'.$image['img'];
			$data['img_w'] = intval($image['img_x2']) - intval($image['img_x1']);
			$data['img_h'] = intval($image['img_y2']) - intval($image['img_y1']);
			
			$data['original'] = Won::get('Config')->content_url.'/'.$this->contentFolder.'/'.$image['original'];
			$data['original_w'] = $image['original_w'];
			$data['original_h'] = $image['original_h'];
			
			$data['thumb'] = Won::get('Config')->content_url.'/'.$this->contentFolder.'/'.$image['thumb'];
			$data['thumb_w'] = intval($image['thumb_x2']) - intval($image['thumb_x1']);
			$data['thumb_h'] = intval($image['thumb_y2']) - intval($image['thumb_y1']);			
		}
		return $data;
	}
	
	public function getImages($ids) {
		
		$data = array();
		if (gettype($ids)=='array' && count($ids)>0) 
		{
			foreach ($ids as $id) {
				$id = (int)$id;
				$img = $this->getImage($id);
				if ($img) {
					$data[]=$img;
				}
			}			
		}
		return $data;		
	}
	
	public function addImage($imagePath, $w, $h, $tw, $th) {
		
		$name = basename($imagePath);
		$folder = Won::get('Config')->content_dir . '/' . $this->contentFolder;
		$r = $w / $h;
				
		// Original
		$origImg = new Image($imagePath);
		$origName = preg_replace('#(^.+?)(\.[a-zA-Z]+?)$#', '$1_original$2' , $name);				
		$origImg->save($folder . '/' . $origName);
		
		// Resized
		if ($r > $origImg->ratio) { // desinated width is longer
			$reImg = $origImg->resize(array('w'=>$w));
			$rep1 = array('x'=>0, 'y'=>($reImg->height-$h)/2);
			$rep2 = array('x'=>$reImg->width, 'y'=>($reImg->height-$h)/2+$h);
		}
		else { // desinated height is longer
			$reImg = $origImg->resize(array('h'=>$h));
			$rep1 = array('x'=>($reImg->width-$w)/2, 'y'=>0);
			$rep2 = array('x'=>($reImg->width-$w)/2+$w, 'y'=>$reImg->height);
		}		
		$reCrop = $reImg->crop($rep1,$rep2);
		$reName = $name;
		$reCrop->save($folder . '/' . $reName);
		
		// Thumb
		$r = $tw/$th;
		if ($r > $origImg->ratio) { // desinated width is longer
			$thImg = $origImg->resize(array('w'=>$tw));
			
			$thp1 = array('x'=>0, 'y'=>($thImg->height-$th)/2);
			$thp2 = array('x'=>$thImg->width, 'y'=>($thImg->height-$th)/2+$th);			
		}		
		else {			
			$thImg = $origImg->resize(array('h'=>$th));
			$thp1 = array('x'=>($thImg->width-$tw)/2, 'y'=>0);
			$thp2 = array('x'=>($thImg->width-$tw)/2+$tw, 'y'=>$thImg->height);
		}
		$thCrop = $thImg->crop($thp1,$thp2);
		$thName = preg_replace('#(^.+?)(\.[a-zA-Z]+?)$#', '$1_thumb$2' , $name);
		$thCrop->save($folder . '/' . $thName);		
		
		// Add to DB
		Won::get('DB')->query("
			INSERT INTO `{$this->table}`
			SET `img` = '{$reName}',
				`img_w` = {$reImg->width},
				`img_h` = {$reImg->height},
				`img_x1` = {$rep1['x']},
				`img_y1` = {$rep1['y']},
				`img_x2` = {$rep2['x']},
				`img_y2` = {$rep2['y']},
				`original` = '{$origName}',
				`original_w` = {$origImg->width},
				`original_h` = {$origImg->height},
				`thumb` = '{$thName}',
				`thumb_w` = {$thImg->width},
				`thumb_h` = {$thImg->height},
				`thumb_x1` = {$thp1['x']},
				`thumb_y1` = {$thp1['y']},
				`thumb_x2` = {$thp2['x']},
				`thumb_y2` = {$thp2['y']}
		");
		return Won::get('DB')->sql->insert_id;	
	}
	
	public function removeImage($id) {
		Won::get('DB')->query("
			DELETE FROM `{$this->table}`
			WHERE `id` = {$id}
		");
	}
	
	
	public function updateImage($id, $width, $height, $x1, $y1, $x2, $y2) {
		
		// get image
		$img = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		$img = $img->fetch_assoc();
				
		$folder = Won::get('Config')->content_dir.'/'.$this->contentFolder;
		
		// Original
		$origImg = new Image($folder . '/' . $img['original']);
		
		// Resized
		$reImg = $origImg->resize(array('w'=>$width, 'h'=>$height));
		
		// Crop
		$rep1 = array('x'=>$x1, 'y'=>$y1);
		$rep2 = array('x'=>$x2, 'y'=>$y2);
		$reCrop = $reImg->crop($rep1,$rep2);
		$reCrop->save($folder . '/' . $img['img']);
		
		// Add to DB
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `img_w` = {$reImg->width},
				`img_h` = {$reImg->height},
				`img_x1` = {$x1},
				`img_y1` = {$y1},
				`img_x2` = {$x2},
				`img_y2` = {$y2}		
			WHERE `id`={$id}
		");		
	}
	
	public function updateThumb($id, $width, $height, $x1, $y1, $x2, $y2) {
		
		// get image
		$img = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		$img = $img->fetch_assoc();
				
		$folder = Won::get('Config')->content_dir.'/'.$this->contentFolder;
		
		// Original
		$origImg = new Image($folder . '/' . $img['original']);
		
		// Resized
		$thImg = $origImg->resize(array('w'=>$width, 'h'=>$height));
		
		// Crop
		$thp1 = array('x'=>$x1, 'y'=>$y1);
		$thp2 = array('x'=>$x2, 'y'=>$y2);
		$thCrop = $thImg->crop($thp1,$thp2);
		$thCrop->save($folder . '/' . $img['thumb']);
		
		// Add to DB
		Won::get('DB')->query("
			UPDATE `{$this->table}`
			SET `thumb_w` = {$thImg->width},
				`thumb_h` = {$thImg->height},
				`thumb_x1` = {$x1},
				`thumb_y1` = {$y1},
				`thumb_x2` = {$x2},
				`thumb_y2` = {$y2}			
			WHERE `id`={$id}
		");		
	}	
	
	
	public function imageEditor($id, $jsCallback) {
		
		// get image
		$img = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		$img = $img->fetch_assoc();
		
		// get image width and height
		/*
		if ($img['original_w']/$img['original_h'] > $img['img_w']/$img['img_h']) {
			$imgw = ($img['img_h']*$img['img_original_w'])/$img['img_original_h'];
			$imgh = $img['img_h'];
		}
		else {
			$imgw = $img['img_w'];
			$imgh = ($img['img_w']*$img['img_original_h'])/$img['img_original_w'];
		}*/
		
		// size of the image before cropped		
		$imgw = $img['img_w'];
		$imgh = $img['img_h'];
		
		$path = Won::get('Config')->content_url.'/'.$this->contentFolder;
		
		//$c .= "$(function(){";
		$c = 	"var editor = $('<div id=\"image-editor\"></div>');";
		$c .= 	"var jcropApi;";
		$c .=	"var target = $(this);";
		
		// Background Opaque Disabler
		$c .=	"var bg=$('<div></div>');";
		$c .=	"bg.css({'width':'100%','height':'100%','background':'#000','position':'fixed','top':'0px','left':'0px','opacity':0.5}).appendTo(editor);";
		
		// Editor BG
		$c .=	"var ebg = $('<div></div>');";
		$c .=	"ebg.css({'width':'80%', 'height':'70%','background':'#fff','position':'fixed','top':'10%','left':'10%','overflow':'auto'}).appendTo(editor);";
		
		// Content Div
		$c .=	"var content = $('<div></div>');";
		$c .=	"content.css({'padding':'20px'}).appendTo(ebg);";
		
		// Image
		$c .=	"var img = $('<img id=\"cropimage\" />');";
		$c .=	"img.attr('src', '{$path}/{$img['original']}').css({'width':'{$imgw}px', 'height':'{$imgh}px'}).appendTo(content);";
		
		// Controls
		$c .= 	"var controls = $('<div></div>');";
		$c .=	"controls.css({'position':'fixed','top':'80%','left':'10%','background':'#fff','width':'80%','margin-top':'2px'}).appendTo(ebg);";
		$c .=	"var controlDiv = $('<div></div>');";
		$c .=	"controlDiv.css({'padding':'10px 20px'}).appendTo(controls);";
		$c .=	"controlDiv.append('Resize: width: ');";		
		$c .=	"var width = $('<input type=\"text\" value=\"{$imgw}\"  />');";
		$c .=	"width.css({'width':'30px'}).appendTo(controlDiv);";
		$c .=	"controlDiv.append(' px - ');";
		$c .=	"controlDiv.append('height: ');";
		$c .=	"var height = $('<input type=\"text\" value=\"{$imgh}\" />');";
        $c .=	"height.css({'width':'30px'}).appendTo(controlDiv);";
		$c .=	"controlDiv.append(' px ');";
		$c .= 	"var ubtn = $('<button>Update image size</button>');";
		$c .=	"ubtn.appendTo(controlDiv);";
		$c .=	"controlDiv.append(' ');";
		$c .=	"var okbtn = $('<button>OK</button>');";
		$c .=	"okbtn.appendTo(controlDiv);";
		$c .=	"var origsize = $('<div>Original Image Size: {$img['original_w']}px by {$img['original_h']}px</div><br/>');";
		$c .=	"origsize.appendTo(controlDiv);";
		
		$c .=	"var xbtn = $('<div>X</div>');";
		$c .=	"xbtn.css({'width':'15px','height':'15px','color':'#fff','font-family':'arial','font-size':'15px','cursor':'pointer','margin':'0px','padding':'0px','position':'fixed','left':'90%','top':'10%','margin-top':'-20px','margin-left':'-10px'}).appendTo(ebg);";
				
		// Controls Event Handlers
		$c .=	"width.keyup(function(e) {";
		$c .=		"height.val(Math.round(({$imgh}*$(this).val())/{$imgw}));";
		$c .=	"});";
		$c .=	"height.keyup(function(e) {";
		$c .=		"width.val(Math.round(({$imgw}*$(this).val())/{$imgh}));";
		$c .=	"});";
		$c .=	"ubtn.click(function() {";
		$c .=		"if (width.val()!='NaN' && height.val()!='NaN')";
		$c .=		"img.css({'width':parseInt(width.val()), 'height':parseInt(height.val())});";
		$c .=		"jcropApi.destroy();";
		$c .=		"img.Jcrop({";
		$c .=		"setSelect : [0,0,width.val(),height.val()]";						
		$c .=		"}, function() {";
		$c .=			"jcropApi = this;";
		$c .=		"});";	
		$c .=	"});";
		$c .=	"xbtn.click(function(){";
		$c .=		"editor.remove();";
		$c .=	"});";
		
		// Start
		$c .=	"img.Jcrop({";
		$c .=		"setSelect : [{$img['img_x1']},{$img['img_y1']},{$img['img_x2']},{$img['img_y2']}]";			
		$c .=	"}, function() {";
		$c .=		"jcropApi = this;";
		$c .=	"});";
				
		// When OKed
		$c .= 	"okbtn.click(function(){";
		$c .=		"var data = jcropApi.tellSelect();";
		$c .=		"data.width = width.val();";
		$c .=		"data.id = {$id};";
		$c .=		"data.height = height.val();";
		$c .=		"{$jsCallback}(data,target);";
		$c .=		"editor.remove()";
		$c .=	"});";
		
		$c .=	"$('body').append(editor);";
		
		
		return $c;
	}
	
	public function thumbEditor($id, $jsCallback) {
		
		// get image
		$img = Won::get('DB')->query("
			SELECT * FROM `{$this->table}`
			WHERE `id` = {$id}
		");
		$img = $img->fetch_assoc();
		
		// get thumb width / height
		/*if ($img['img_original_w']/$img['img_original_h']>$img['img_thumb_w']/$img['img_thumb_h']) {
			$imgw = ($img['img_thumb_h']*$img['img_original_w'])/$img['img_original_h'];
			$imgh = $img['img_thumb_h'];
		} 
		else {
			$imgw = $img['img_thumb_w'];
			$imgh = ($img['img_thumb_w']*$img['img_original_h'])/$img['img_original_w'];
		}*/
		
		$imgw = $img['thumb_w'];
		$imgh = $img['thumb_h'];
		
		//$thw = $thumbSize['width'];
		//$thh = $thumbSize['height'];
		
		$path = Won::get('Config')->content_url.'/'.$this->contentFolder;
		
		$c = 	"var editor = $('<div id=\"thumb-editor\"></div>');";
		$c .= 	"var jcropApi;";
		$c .=	"var target = $(this);";
		
		// Background Opaque Disabler
		$c .=	"var bg=$('<div></div>');";
		$c .=	"bg.css({'width':'100%','height':'100%','background':'#000','position':'fixed','top':'0px','left':'0px','opacity':0.5}).appendTo(editor);";
		
		// Editor BG
		$c .=	"var ebg = $('<div></div>');";
		$c .=	"ebg.css({'width':'{".($imgw+40)."}px', 'height':'{".($imgh+40)."}','background':'#fff','position':'fixed','top':'50%','left':'50%','margin-left':'-".(($imgw+40)/2)."px','margin-top':'-".(($imgh+40)/2)."px'}).appendTo(editor);";
		
		// Content Div
		$c .=	"var content = $('<div></div>');";
		$c .=	"content.css({'padding':'20px'}).appendTo(ebg);";
		
		// Image
		$c .=	"var img = $('<img id=\"cropimage\" />');";
		$c .=	"img.attr('src', '{$path}/{$img['original']}').css({'width':'{$imgw}px', 'height':'{$imgh}px'}).appendTo(content);";
		
		// Controls
		$c .= 	"var controls = $('<div></div>');";
		
		$c .=	"controls.css({'position':'fixed','top':'50%','left':'50%','background':'#fff','width':'".($imgw+40)."px','margin-top':'".(($imgh+40)/2+2)."px','margin-left':'-".(($imgw+40)/2)."px'}).appendTo(ebg);";
		$c .=	"var controlDiv = $('<div></div>');";
		$c .=	"controlDiv.css({'padding':'5px 10px'}).appendTo(controls);";
		$c .=	"var okbtn = $('<button>OK</button>');";
		$c .=	"okbtn.appendTo(controlDiv);";
		$c .=	"var xbtn = $('<div>X</div>');";
		$c .=	"xbtn.css({'width':'15px','height':'15px','color':'#fff','font-family':'arial','font-size':'15px','cursor':'pointer','margin':'0px','padding':'0px','position':'fixed','left':'50%','top':'50%','margin-top':'-".((($imgh+40)/2)+16)."px','margin-left':'".((($imgw+40)/2)-10)."px'}).appendTo(ebg);";
				
		// Controls Event Handlers
		$c .=	"xbtn.click(function(){";
		$c .=		"editor.remove();";
		$c .=	"});";
		
		// Start
		$c .=	"img.Jcrop({";
		$c .=		"setSelect : [{$img['thumb_x1']},{$img['thumb_y1']},{$img['thumb_x2']},{$img['thumb_y2']}],";
		$c .=		"allowResize : false";			
		$c .=	"}, function() {";
		$c .=		"jcropApi = this;";
		$c .=	"});";
				
		// When OKed
		$c .= 	"okbtn.click(function(){";
		$c .=		"var data = jcropApi.tellSelect();";
		$c .=		"data.id = {$id};";
		$c .=		"data.width = {$imgw};";
		$c .=		"data.height = {$imgh};";
		$c .=		"{$jsCallback}(data,target);";
		$c .=		"editor.remove()";
		$c .=	"});";
		
		$c .=	"$('body').append(editor);";
		
		return $c;
		
	}
}






















?>