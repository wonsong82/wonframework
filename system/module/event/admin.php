<?php
// namespace app\module;
class app_module_EventAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		// List Page
		$p = $this->newPage('list');
		$p->text = $this->getText('Events');
		$this->addPage($p);
		$f = $this->newButton('add-btn');
		$f->text = $this->getText('Add');
		$f->desc = $this->getText('Add a New Event');
		$f->redirect('event.add');
		$p->addChild($f);
		$t = $this->newTable('events');
		$t->linkData($this->event->getList(true), true);
		$t->order('event', 'event');
		$f = $this->newButton('edit-btn');
		$f->text = $this->getText('Detail');
		$f->redirect('event.edit', 'parent.parent.rowid');
		$t->addChild($f);
		$f = $this->newButton('del-btn');
		$f->text = $this->getText('Delete');
		$f->action('event.remove', 'parent.parent.rowid');
		$f->redirect('event.list');
		$t->addChild($f);
		$p->addChild($t);	
		
		$p = $this->newPage('add');
		$p->text = $this->getText('Add Event');
		$p->desc = 'Add an Event by Providing Event Name';
		$this->addPage($p, false);
		$f = $this->newTextField('name');
		$f->text = $this->getText('Event Name');
		$p->addChild($f);
		$f = $this->newButton('ok-btn');
		$f->text = $this->getText('Ok');
		$f->action('event.add', '#name');
		$f->redirect('event.list');
		$p->addChild($f);
		
		$event = $this->event->getEvent($this->rowid);
		$p = $this->newPage('edit');
		$p->text = $this->getText('Detail of the Event');
		$this->addPage($p, false);
		
		
		
		$f = $this->newTextField('name');
		$f->text = $this->getText('Event Name');
		$f->linkData($event['title']);
		$f->css = '#name-wrap input{width:420px}';
		$p->addChild($f);
		
		$f = $this->newTextField('subtitle');
		$f->text = $this->getText('Subtitle');
		$f->linkData($event['subtitle']);
		$f->action('event.update', 'event.subtitle', $this->rowid, '#subtitle');
		$f->css = '#subtitle-wrap input{width:420px}';
		$p->addChild($f);
			
		$f = $this->newTextField('uname');
		$f->text = $this->getText('Unique Name');
		$f->static = true;
		$f->linkData($event['uname']);
		$p->addChild($f);
		
		$f = $this->newButton('name-btn');
		$f->text = $this->getText('Update Event Name');
		$f->action('event.updateTitle', $this->rowid, '#name');
		$f->redirect('event.edit', 'parent.rowid');
		$p->addChild($f);
		
		$p->addChild('<br/><hr/><br/>');
		
		$f = $this->newDatePicker('start');
		$f->text = $this->getText('Start Date');
		$f->linkData(date('Y-m-d', strtotime($event['start'])));
		$f->action('event.update', 'event.start', $this->rowid, '#start');
		$p->addChild($f);
		$f = $this->newDatePicker('end');
		$f->text = $this->getText('End Date');
		$f->linkData(date('Y-m-d', strtotime($event['end'])));
		$f->action('event.update', 'event.end', $this->rowid, '#end');
		$p->addChild($f);
		$f = $this->newDatePicker('posted');
		$f->text = $this->getText('Posted On');
		$f->linkData(date('Y-m-d', strtotime($event['posted'])));
		$f->action('event.update', 'event.posted', $this->rowid, '#posted');
		$p->addChild($f);
		$p->addChild('<br/><hr/><br/>');
		
				
		$f = $this->newText('content');
		$f->text = $this->getText('Content');
		$f->linkData($this->content->getContent($event['content_id'], true));
		$p->addChild($f);
		$f = $this->newButton('content-btn');
		$f->text = $this->getText('Update Content');
		$f->action('content.update', 'content.content', $event['content_id'], '#content');
		$f->message = $this->getText('Saved');
		$p->addChild($f);
		
		$p->addChild('<br/><hr/><br/>');
		
		// Image Uploader
		if($event['img']===false){
			$f = $this->newUploader('add-img-btn');
			$f->text = $this->getText('Add an Image');
			$f->type = 'image';
			$f->imgWidth = 760;
			$f->imgHeight = 400;
			$f->thumbWidth = 310;
			$f->thumbHeight = 110;
			$f->action('event.addImage', $this->rowid);
			$f->redirect('event.edit', 'rowid='.$this->rowid);
			$p->addChild($f);
		} 
		
		// Image Editor
		else {
			$f = $this->newImageEditor('image-editor');
			$f->linkData($event['img_id']);
			$p->addChild($f);
		}
		
		$f = $this->newButton('go-back-btn');
		$f->text = $this->getText('Go Back to the List');
		$f->redirect('event.list');
		$p->addChild($f);
		
	}
	
}

?>
