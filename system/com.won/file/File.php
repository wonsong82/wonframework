<?php
// namespace com\won\file;
final class com_won_file_File {
	
	public function __construct(){}
	
	// write into the file and returns pathinfo array
	public function write($filepath, $content, $overwrite=false) {
		$filepath = $overwrite? $filepath : $this->autoRename($filepath);
		if (!is_dir(dirname($filepath))) {
			mkdir(dirname($filepath)) or die("Cannot make a directory for {$filepath}");
		}
		$fh = fopen($filepath,'w') or die("Cannot create a file for {$filepath}");
		fwrite($fh, $content);
		fclose($fh);
		return pathinfo($filepath);
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
	
		
}
?>