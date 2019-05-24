<?php
// namespace app\module;
class app_module_ImageAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		$page = $this->newPage('images-page');
		$page->text = $this->getText('Images');
		$this->addPage($page);	
		
		$addBtn = $this->newUploader('add-image-btn');
		$addBtn->text = $this->getText('Add Image');
		$addBtn->type = 'image';
		$addBtn->imgWidth = 900;
		$addBtn->imgHeight = 600;
		$addBtn->thumbWidth = 50;
		$addBtn->thumbHeight = 50;
		$addBtn->redirect('image.images-page');
		$page->addChild($addBtn);
		
		$table = $this->newTable('images-list');
		$table->linkData($this->image->getImages());
		$table->order('image', 'image');
		$page->addChild($table);
		
		$editBtn = $this->newButton('edit-image-btn');
		$editBtn->text = $this->getText('Edit');
		$editBtn->redirect('image.edit-page', 'parent.parent.rowid');
		$table->addChild($editBtn);
		
		$delBtn = $this->newButton('delete-image-btn');
		$delBtn->text = $this->getText('Delete');
		$delBtn->action('image.remove', 'parent.parent.rowid');
		$delBtn->redirect('image.images-page');
		$table->addChild($delBtn);
		
		$editPage = $this->newPage('edit-page');
		$editPage->text = $this->getText('Edit Image');
		$this->addPage($editPage,false);
		
		$imgEditor = $this->newImageEditor('image-editor');
		$imgEditor->text = '[img#'.$this->rowid.']';
		$imgEditor->linkData($this->rowid);
		$editPage->addChild($imgEditor);
		
		$oKBtn = $this->newButton('edit-ok-btn');
		$oKBtn->text = $this->getText('Go Back');
		$oKBtn->redirect('image.images-page');
		$editPage->addChild($oKBtn);
		
		
		
		
	}
	
}

?>
