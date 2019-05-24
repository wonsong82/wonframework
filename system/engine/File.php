<?php
// Name : File
// Desc : File

// namespace app\engine;
final class app_engine_File {
	
	
	private $reg;
	private $error;
	
	//
	// Constructor
	public function __construct($reg){
		$this->reg = $reg;
		$this->error = '';
	}
	
	//
	// For the access of Engines & Modules from Views
	public function __get($name){
		return $this->reg->$name;
	}
	
	//
	// Upload the file to the uploadDir
	public function upload($rename=true){
		$file = $this->req->files['file'];
		// Check for error
		if((int)$file['error'] > 0){
			$this->error = 'Uploading Error';
			return false;
		}
		$filePath = $this->config->uploadDir . $file['name'];
		$filePath = $rename? $this->autoRename($filePath) : $filePath; 	
		
		if(!move_uploaded_file($file['tmp_name'], $filePath)){
			$this->error = 'Uploading Error While Moving';
			return false;	
		}
		
		return $filePath;
	}
	
	//
	// Upload the parts to the uploadDir
	public function uploadPart($rename=true){
		$isFirst = (bool)$this->req->post['first'];
		$isLast = (bool)$this->req->post['last'];
		
		$name = $this->req->post['name'];
		
		$filePath = $this->config->uploadDir . $name; // Final FilePath
		$filePath = $rename? $this->autoRename($filePath) : $filePath;
		
		// Save to the name.part file
		$buildPath = $this->config->uploadDir . $name . '.part';
		$fh = $isFirst? fopen($buildPath, 'w') : fopen($buildPath, 'a');
		$bytes = preg_replace('#^.*,#','',$this->req->post['data']);
		fwrite($fh, base64_decode($bytes));
		fclose($fh);
		
		// if Last one, Finalize the File
		if($isLast){
			rename($buildPath, $filePath);
		}
		
		return $filePath;
	}
	
	// Return the renamed filepath for duplicates
	public function autoRename($filepath) {
		$info = pathinfo($filepath);
		$dir = isset($info['dirname'])? $info['dirname'].'/' : '';
		$filename = isset($info['filename'])? $info['filename'] : '';		
		$extension = isset($info['extension'])? '.'.$info['extension'] : '';
		
		while (file_exists($dir.$filename.$extension)) {
			if (!preg_match('/-([0-9]+)$/',$filename,$match)) {
				$filename.='-2';
			}
			else {
				$filename=preg_replace('/[0-9]+$/','',$filename).(intval($match[1])+1);				
			}
		}
		return $dir.$filename.$extension;		
	}
	
	public function lastError(){
		return $this->error;
	}
}

?>