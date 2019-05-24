<?php
// namespace app\module;
final class app_module_EventController extends app_engine_Controller{
	
	public function add($title){
		
		// Validate Input
		if(!$this->model->field('event.title')->validate($title)){
			$this->error = 'Title is in invalid format.';
			return false;
		}
		
		// Title
		$title = $this->db->escape($title);
		
		// Unique Name
		$uname = $this->uname->uName($title);
		$this->uname->add($uname);
		$unameId = $this->db->insertId();
		
		// Empty Image
		
		// Content
		$a=$this->content->add($uname, $unameId);
		$contentId = $this->db->insertId();
		
		// Time
		$time = date('Y-m-d H:i:s');
		
		// Next Order
		$next = $this->nextOrder('event');
		
		$result = $this->model->query("
			INSERT INTO [event]
			SET [event.start] = '{$time}',
				[event.end] = '{$time}',
				[event.posted] = '{$time}',
				[event.title] = '{$title}',
				[event.uname_id] = {$unameId},
				[event.content_id] = {$contentId},
				[order] = {$next}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		
		return true;		
	}
	
	public function addImage($imgId, $eventId){
		return $this->update('event.img_id', $eventId, $imgId);
	}
	
	public function updateTitle($id, $title){
		
		$id = (int)$id;
		$event = $this->getEvent($id);
		if(false===$event){
			return false;
		}
		
		$result = $this->update('event.title', $id, $title);
		if(false===$result){
			return false;
		}
		
		$result = $this->uname->update($event['uname_id'], $title);
		if(false===$result){
			$this->error = $this->uname->lastError();
			return false;
		}
		
		return true;
	}
	
	public function remove($id){
		$id = (int)$this->db->escape($id);
		$result = $this->model->query("
			SELECT  [event.uname_id] AS [uname_id],
					[event.content_id] AS [content_id],
					[event.img_id] AS [img_id]
			FROM [event]
			WHERE [event.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Invalid Event ID';
			return false;
		}
		
		$imgid = (int)$result[0]['img_id'];
		$contentid = (int)$result[0]['content_id'];
		$unameid = (int)$result[0]['uname_id'];
		
		$result = $this->model->query("
			DELETE FROM [event]
			WHERE [event.id] = {$id}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}		
		$this->image->remove($imgid);
		$this->uname->remove($unameid);
		$this->content->remove($contentid);
		
		return true;
	}
	
	public function getList($admin=false){
		$result = $this->model->query("
			SELECT 	[event.id] AS [id],
					[event.title] AS [title]
			FROM [event]
			ORDER BY [order]
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!$admin){
			foreach($result as &$event){
				$event = $this->getEvent($event['id']);
			}
		}		
		
		return $result;
	}
	
	// Retrieve Single Event, by ID or uname
	public function getEvent($param){
		if((int)$param > 0){
			$id = (int)$param;
			$where = "[event.id] = {$id}";
		}
		else{
			$uname = $this->uname->get($param);
			if(false===$uname){
				$this->error = $this->uname->error;
				return false;
			}			
			$where = "[event.uname_id] = {$uname['id']}"; 
		}
		$result = $this->model->query("		
				SELECT	[event.id] AS [id],
						[event.title] AS [title],
						[event.subtitle] AS [subtitle],
						[event.start] AS [start],
						[event.end] AS [end],
						[event.posted] AS [posted],
						[event.img_id] AS [img_id],
						[event.uname_id] AS [uname_id],
						[event.content_id] AS [content_id]
				FROM [event]
				WHERE {$where}
		");
		if(false===$result){
			$this->error = $this->db->lastError();
			return false;
		}
		if(!count($result)){
			$this->error = 'Unexisting Event';
			return false;
		}
		
		$result = $result[0];
		$uname = $this->uname->get($result['uname_id']);
		$result['img'] = $this->image->getImage($result['img_id']);
		$result['content'] = $this->content->getContent($result['content_id']);
		$result['uname'] = $uname['name'];
		return $result;
	}
	
	
	
}
?>