<?php
// Name : Gallery Model
// Desc : PHoto Gallery

// namespace app\module;
class app_module_GalleryAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		// List Page
		$p = $this->newPage('list');
		$p->text = $this->getText('Photo Gallery');
		$this->addPage($p);
		
		$f = $this->newUploader('add-photo-btn');
		$f->text = $this->getText('Add Photo');
		$f->type = 'image';
		$f->imgWidth = 1400;
		$f->imgHeight = 800;
		$f->thumbWidth = 120;
		$f->thumbHeight = 70;
		$f->action('gallery.add');
		$f->redirect('gallery.list');
		$p->addChild($f);
		
		$t = $this->newTable('gallery-table');
		$t->linkData($this->gallery->getList(true));
		$t->order('gallery', 'gallery');
		$p->addChild($t);
		
		$f = $this->newButton('edit-btn');
		$f->text = $this->getText('Detail');
		$f->redirect('gallery.edit', 'parent.parent.rowid');
		$t->addChild($f);
		
		$f = $this->newButton('del-btn');
		$f->text = $this->getText('Delete');
		$f->action('gallery.remove', 'parent.parent.rowid');
		$f->redirect('gallery.list');
		$t->addChild($f);
		
		
		// Edit Page
		$p = $this->newPage('edit');
		$p->text = $this->getText('Detail of the Photo');
		$this->addPage($p, false);
		
		$f = $this->newTextField('title');
		$f->text = $this->getText('Title');
		$f->linkData($this->gallery->select('gallery.title', $this->rowid));
		$f->action('gallery.update', 'gallery.title', $this->rowid, '#title');
		$p->addChild($f);
		
		$f = $this->newTextField('desc');
		$f->text = $this->getText('Description');
		$f->linkData($this->gallery->select('gallery.desc', $this->rowid));
		$f->action('gallery.update', 'gallery.desc', $this->rowid, '#desc'); 
		$p->addChild($f);
		
		$f = $this->newCheckBox('is-banner');
		$f->text = $this->getText('Main Banner');
		$f->linkData($this->gallery->select('gallery.is_banner', $this->rowid));
		$f->action('gallery.update', 'gallery.is_banner', $this->rowid, '#is-banner');
		$p->addChild($f);
		
		
		$f = $this->newImageEditor('image-editor');
		$f->linkData($this->gallery->getImgId($this->rowid));
		$p->addChild($f);
		
		$f = $this->newButton('go-back-btn');
		$f->text = $this->getText('Go Back');
		$f->redirect('gallery.list');
		$p->addChild($f);
	}
	
}

?>
