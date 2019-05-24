<?php
// namespace app\module;
class app_module_ContentAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		$contentPage = $this->newPage('content-page');
		$contentPage->text = $this->getText('Contents');
		$this->addPage($contentPage);
		
		$addBtn = $this->newButton('content-add-btn');
		$addBtn->text = $this->getText('Add');
		$addBtn->redirect('content.add-page');
		$contentPage->addChild($addBtn);
		
		$list = $this->newTable('content-list');
		$list->linkData($this->content->getContentList());
		$list->order('content','content');
		$contentPage->addChild($list);
		
		$editBtn = $this->newButton('content-edit-btn');
		$editBtn->text = $this->getText('Edit');
		$editBtn->redirect('content.edit-page', 'parent.parent.rowid');
		$list->addChild($editBtn);
		
		$removeBtn = $this->newButton('content-remove-btn');
		$removeBtn->text = $this->getText('Remove');
		$removeBtn->action('content.remove', 'parent.parent.rowid');
		$removeBtn->redirect('content.content-page');
		$list->addChild($removeBtn);
		
				
		
		$addPage = $this->newPage('add-page');
		$addPage->text = $this->getText('Add Content');
		$addPage->desc = $this->getText('Add Desc');
		$this->addPage($addPage, false);
		
		$nameTf = $this->newTextField('name-tf');
		$nameTf->text = $this->getText('Unique Name');
		$addPage->addChild($nameTf);
		
		$okBtn = $this->newButton('ok-btn');
		$okBtn->text = $this->getText('Ok');
		$okBtn->action('content.add', '#name-tf');
		$okBtn->redirect('content.content-page');
		$addPage->addChild($okBtn);
		
		$backBtn = $this->newButton('back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('content.content-page');
		$addPage->addChild($backBtn);
		
		
		$editPage = $this->newPage('edit-page');
		$editPage->text = $this->getText('Edit Content');
		$this->addPage($editPage, false);
		
		$nameTf = $this->newTextField('name-tf');
		$nameTf->text = $this->getText('Unique Name');
		$nameTf->linkData($this->content->select('content.name', $this->rowid));
		$nameTf->static = true;
		$editPage->addChild($nameTf);
		
		$text = $this->newText('content-text');
		$text->text = $this->getText('Content');
		$text->linkData($this->content->getContent($this->rowid, true));
		$editPage->addChild($text);
		
		$okBtn = $this->newButton('ok-btn');
		$okBtn->text = $this->getText('Save');
		$okBtn->action('content.update', 'content.content', $this->rowid, '#content-text');
		$okBtn->message = $this->getText('Saved');
		$editPage->addChild($okBtn);
		
		$backBtn = $this->newButton('back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('content.content-page');
		$editPage->addChild($backBtn);	
	}
	
}

?>
