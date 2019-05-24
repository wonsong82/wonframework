<?php
// Name : Gallery Controller
// Desc : Gallery

// namespace app\module;
final class app_module_GalleryController extends app_engine_Controller{
	
	public function add($imgID){
		$imgID = (int)$this->db->escape($imgID);
		$next = $this->nextOrder('gallery');
		$result = $this->model->query("
			INSERT INTO [gallery]
			SET	[gallery.imgid] = {$imgID},
				[order] = {$next}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		return true;
	}
	
	public function remove($id){
		$id = (int)$this->db->escape($id);
		$result = $this->model->query("
			SELECT [gallery.imgid] AS [imgid]
			FROM [gallery]
			WHERE [gallery.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Invalid Photo ID';
			return false;
		}
		$imgid = (int)$result[0]['imgid'];
		$result = $this->model->query("
			DELETE FROM [gallery]
			WHERE [gallery.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		$this->image->remove($imgid);
		
		return true;
	}
	
	public function getImgId($id){
		$result = $this->model->query("
			SELECT [gallery.imgid] AS [imgid]
			FROM [gallery]
			WHERE [gallery.id] = {$id}
		");		
		return isset($result[0])?(int)$result[0]['imgid']:false;
	}
	
	public function getList($admin=false){
		$query = $admin?
			"SELECT [gallery.id] AS [id],
					[gallery.imgid] AS [img]
			 FROM [gallery] 
			 ORDER BY [order]" :
			
			"SELECT [gallery.id] AS [id],
					[gallery.title] AS [title],
					[gallery.desc] AS [desc],
					[gallery.is_banner] AS [is_banner],
					[gallery.imgid] AS [img]
			FROM [gallery]
			ORDER BY [order]"; 
		
		$result = $this->model->query($query);
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		if(count($result)){
			foreach($result as &$resultPhoto){
				$img = $this->image->getImage($resultPhoto['img']);
				if($admin){
					$resultPhoto['img'] = '<img src="'.$img['t_src'].'"/>';
				}
				else{
					$resultPhoto['img'] = $img;
				}
			}
		}
		
		return $result;
	}
	
}
?>