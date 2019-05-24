<?php

class File extends WonClass
{
	/**
	 * @name $name
	 * @desc File Name if upload completed successfully
	 * @type string
	 */	
	public $name;
	
	/**
	 * @name $path
	 * @desc File Path if upload completed successfully.
	 * @type string
	 */	
	public $path;	
	
	/**
	 * @name $type
	 * @desc File Type if upload completed successfully.
	 * @type string
	 */	
	public $type;
	
	/**
	 * @name $size
	 * @desc File Size if upload completed succesfully.
	 * @type int
	 */	
	public $size;
	
	
	
	/**
	 * @name File()
	 * @desc Control file system
	 * @param none
	 * @return void
	 */
	protected function init()
	{
		
	}	
	
	
	/**
	 * @name load($file)
	 * @desc Set the file into the class, returns true or false (if not success)
	 * @param string $file : Full file path
	 * @return bool
	 */
	public function load($file)
	{
		if (!file_exists($file) )
		{
			$this->error = 'File does not exist.';
			return false;
		}
		
		$this->name = basename($file);
		$this->path = $file;
		$this->type = '';
		$this->size = filesize($file);
		return $this;
	}
	
	
	
	
	/**
	 * @name upload($file, $upload_dir, $rename)
	 * @desc Upload the file from the $_FILES passed through FORM(only). 
	 * @param array $file : $_FILES. Must been passed through FORM
	 * @param string $upload_dir : Upload path
	 * @param bool $rename : Set to overwrite existing file or rename.
	 * @return bool (Returns true or false.)
	 */
	public function upload($file, $upload_dir, $rename=true)
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
		
		// create a directory if not exists
		if (!is_dir($upload_dir))
			mkdir($upload_dir);
		
		// file path		
		$upload_to = rtrim($upload_dir, '/') . '/' . $file['name'];
		
		// if already exists
		if ($rename) 
		{
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
		
		return $this;
	}	
		
	
	public function copy($newpath)
	{
		if (!$this->path)
		{
			$this->error = 'Copy Error : The File has not been initiated.';	
			return false;
		}
		
		if (!copy($this->path, $newpath))
		{
			$this->error = 'Copy Error : Copy failed.';
			return false;
		}
		
		return new File($newpath);
	}
		
		
	/**
	 * @name rename($newname)
	 * @desc Rename the current file 
	 * @param string $newname : Name to be replaced with
	 * @return bool (Returns true or false.)
	 */	
	public function rename($newname)
	{
		if (!$this->path)
		{
			$this->error = 'Rename Error : The File has not been initiated.';	
			return false;
		}
		
		$newpath = str_replace($this->name, $newname, $this->path);
		if (!rename($this->path, $newpath))
		{ 
			$this->error = 'Rename Error : Rename failed.';
			return false;
		}
		
		$this->name = $newname;
		$this->path = $newpath;
		
		return $this;
	}
	
	
function uploader($uploadPath=null, $accept="*", $jsCallback ) {
	
		if (!$uploadPath)
			$uploadPath = Won::get('Config')->content_dir . '/uploads';
	
				
		// uploader
		$data = "var uploader = $('<div id=\"uploader\"><div>');";
		$data .= "var target = $(this);";
		// opacity background
		$data .= "var bg = $('<div></div>');";	
		$data .= 		"bg.css({'width':'100%','height':'100%','background':'#000','position':'fixed','top':'0px','left':'0px','opacity':0.5});";	
		$data .= "uploader.append(bg);";	
		
		// form
		$data .= "var form = $('<form></form>');";								
		$data .= "form.css({'width':'260px','height':'30px','background':'#fff','position':'fixed','top':'50%','left':'50%','margin-left':'-130px','margin-top':'-20px','padding':'20px','border-radius':'4px','opacity':.8});";		
		$data .= "var file = $('<input type=\"file\" name=\"file\" accept=\"{$accept}\" />');";		
		$data .= "var path = $('<input type=\"hidden\" name=\"path\" value=\"{$uploadPath}\" />');";	
		$data .= "file.css({'width':'auto','margin':'0px'});";		
		
		// When file is changed, make a iframe and upload 
		$data .= "file.change(function() {";		
		$data .= 	"var iframe = $('<iframe width=\"200\" height=\"200\", border=\"0\"></iframe>');";		
		$data .= 	"iframe.css({'display':'none'});";	
		$data .= 	"iframe.attr('name','uploader_iframe');";		
		$data .= 	"iframe.attr('id','uploader_iframe');";		
		$data .= 	"form.remove();";					
		$data .= 	"xbtn.remove();";	
		$data .= 	"var loading = $('<div></div>').addClass('loading');";
		$data .= 	"loading.css({'position':'fixed','top':'50%','left':'50%'});";
		$data .= 	"uploader.append(loading);";		
		$data .= 	"uploader.append(iframe);";		
		$data .= 	"var frame = document.getElementById('uploader_iframe');";			
		$data .= 	"var content = '';";			
		$data .= 	"if (frame.addEventListener) {";		
		$data .= 		"frame.addEventListener('load', function(){";					
		$data .= 			"if (frame.contentDocument) {";																							
		$data .= 				"content = frame.contentDocument.body.innerHTML;";					
		$data .= 			"} else if (frame.contentWindow) {";	
		$data .= 				"content = frame.contentWindow.document.body.innerHTML;";			
		$data .= 			"} else if (frame.document) {";		
		$data .= 				"content = frame.document.body.innerHTML;";	
		$data .= 			"}";			
		$data .= 			"var data = $.parseJSON(content);";
		$data .=			"if (data.status && data.status == 1) {";
		$data .=				"{$jsCallback} (data.file.name, target);";					
		$data .=			"} else {";					
		$data .=				"alert ('uploading error.');";						
		$data .=			"}";
		$data .=			"uploader.remove();";
		$data .=			"});";
		$data .=	"}";
		$data .=	"else if (frame.attachEvent) {";				
		$data .=		"frame.attachEvent('onload', function(){";						
		$data .=			"if (frame.contentDocument) {";			
		$data .=				"content = frame.contentDocument.body.innerHTML;";
		$data .=			"} else if (frame.contentWindow) {";
		$data .=				"content = frame.contentWindow.document.body.innerHTML;";
		$data .=			"} else if (frame.document) {";
		$data .=				"content = frame.document.body.innerHTML;";
		$data .=			"}";
		$data .=			"var data = $.parseJSON(content);";
		$data .=			"if (data.status && data.status == 1) {";		
		$data .=				"{$jsCallback} (data.file.name, target);";	
		$data .=			"} else {";
		$data .=					"alert ('uploading error.');";
		$data .=			"}";
		$data .=			"uploader.remove();";			
		$data .=		"});";
		$data .=	"}";
		
		// set up forms
		$data .=	"form.attr('target','uploader_iframe');";
		$data .=	"form.attr('action', '" . Won::get('Config')->module_url.'/Core/ajax/uploader.php' . "');";
		$data .=	"form.attr('method','post');";
		$data .=	"form.attr('enctype','multipart/form-data');";
		$data .=	"form.attr('encoding','multipart/form-data');";				
		$data .=	"form.submit();";
		$data .="});";
		
		$data .="form.append(file);";
		$data .="form.append(path);";
		$data .="uploader.append(form);";
		
		// close button
		$data .="var xbtn = $('<div>X</div>');";
		$data .="xbtn.css({'width':'15px','height':'15px','color':'#fff','font-family':'arial','font-size':'15px','cursor':'pointer','margin':'0px','padding':'0px','position':'fixed','left':'50%','top':'50%','margin-top':'-40px','margin-left':'160px'});";
		$data .="xbtn.click(function(){";
		$data .=	"uploader.remove();";
		$data .="});";
		$data .="uploader.append(xbtn);";
		
		$data .="$('body').append(uploader);";
		
		return $data;						
	}
}
