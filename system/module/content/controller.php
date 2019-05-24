<?php
// namespace app\module;
final class app_module_ContentController extends app_engine_Controller{
	
	// @override
	// Constructor
	public function __construct($registry){
		parent::__construct($registry);
		
	}
	
	public function add($name, $unameId=false){
		if(!$this->model->field('content.name')->validate($name)){
			$this->error = 'Invalid Name Format';
			return false;
		}
		
		if($unameId===false){
			$name = $this->uname->uName($name);
			$this->uname->add($name);
		}
		elseif((int)$unameId>0){
			$uname = $this->uname->get($unameId);
			$name = $uname['name'];
		}
		else{
			$this->error = 'Invalid Unique Name ID';
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
			SELECT [content.name] AS [name] FROM [content]
			WHERE [content.id]={$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Unexisting Content';
			return false;
		}
		$name = $result[0]['name'];
		$this->uname->remove($name);
				
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
			ob_start();
			eval(' ?>'.htmlspecialchars_decode($this->replaceShortkeys($content)));
			$cs = ob_get_contents();
			ob_end_clean();
			return $cs;
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