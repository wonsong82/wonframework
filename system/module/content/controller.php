<?php
namespace app\module;
final class ContentController extends \app\engine\Controller{
	
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		/*
		// Import Packages
		$this->phpassObj = $this->loader->getClass('auth.PHPass');
		$this->codeObj = $this->loader->getClass('auth.Code');
		
		// $sessionName is what client will use for their session and form
		$this->sessionName = 'webwon_user';
		$this->encodedSessionName = $this->codeObj->encrypt($this->sessionName);	
		$this->encodedIsAdmin = $this->codeObj->encrypt('isadmin');
		
		$this->isAdmin = false;
		$this->logged = false;	*/
	}
	
	public function add($name){
		if(!$this->model->field('content.name')->validate($name)){
			$this->error = 'Invalid Name Format';
			return false;
		}
		
		$next = $this->nextOrder('content');
		$result = $this->model->query("
			INSERT INTO [content]
			SET [content.name]='{$name}',
				[order] = {$next}
		");
		if($result===false){
			$this->error = 'Add Content Error';
			return false;
		}
		return true;		
	}
	
	public function remove($id){
		$id=(int)$id;
		$result = $this->model->query("
			DELETE FROM [content]
			WHERE [content.id]={$id}
		");
		if(!$result){
			$this->error = 'Remove Content Error';
			return false;
		}
		return true;
	}
	
	public function getContentList(){
		return $this->model->query("
			SELECT 	[content.id] AS [id],
					[content.name] AS [name]
			FROM	[content]
			ORDER BY [order]
		");
	}
	
	
	public function getContent($name, $edit=false){
		if((int)$name > 0){
			$id = $this->db->escape((int)$name);
			$where = "[content.id] = {$id}";
		} else {
			$name = $this->db->escape($name);
			$where = "[content.name] = '{$name}'";
		}
		$result = $this->model->query("
			SELECT [content.content] AS [content]
			FROM [content]
			WHERE {$where}
		");	
		if(!$result){
			$this->error = 'Get Content Error';
			return false;
		}
		$content = $result[0]['content'];
		
		if($edit) // Edit is for content editor
			return htmlspecialchars_decode($content);
		else{
			eval(' ?>'.htmlspecialchars_decode($this->replaceShortkeys($content)));
			return true;
		}
	}
	
	private function replaceShortkeys($content){
		preg_match_all('/\&\#91\;img\#([0-9]+)\&\#93\;/', $content, $imgs);
		for($i=0;$i<count($imgs[0]);$i++){
			$id = (int)$imgs[1][$i];
			$img = $this->image->getImage($id);
			$content = str_replace($imgs[0][$i], '<img src="'. $img['src']. '"/>', $content);			
		}
		preg_match_all('/\&\#91\;thumb\#([0-9]+)\&\#93\;/', $content, $thumbs);
		for($i=0;$i<count($thumbs[0]);$i++){
			$id = (int)$thumbs[1][$i];
			$img = $this->image->getImage($id);
			$content = str_replace($thumbs[0][$i], '<img src="'. $img['t_src']. '"/>', $content);			
		}
		return $content;
		
	}	

	
	
	
}
?>