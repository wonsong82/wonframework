<?php
// namespace app\module;
class app_module_UrlAdmin extends app_engine_AdminView{
	
	public function construct(){
		
		$uriPage = $this->newPage('uris-page');
		$uriPage->text = $this->getText('Uri');
		$this->addPage($uriPage);
		
		$uriPageAddBtn = $this->newButton('uris-page-add-btn');
		$uriPageAddBtn->text = $this->getText('Add URI');
		$uriPageAddBtn->redirect('url.add-uri-page');
		$uriPage->addChild($uriPageAddBtn);
		
		$uriTable = $this->newTable('uri-list');
		$uriTable->linkData($this->url->getURIs());
		$uriTable->order('url','url');
		$uriPage->addChild($uriTable);
		
		$uriEditBtn = $this->newButton('edit-uri-btn');
		$uriEditBtn->text = $this->getText('Edit');
		$uriEditBtn->redirect('url.edit-uri-page', 'parent.parent.rowid');
		$uriTable->addChild($uriEditBtn);
		
		$uriRemoveBtn = $this->newButton('remove-uri-btn');
		$uriRemoveBtn->text = $this->getText('Remove');
		$uriRemoveBtn->action('url.remove', 'parent.parent.rowid');
		$uriRemoveBtn->redirect('url.uris-page');
		$uriTable->addChild($uriRemoveBtn);
		
		$addPage = $this->newPage('add-uri-page');
		$addPage->text = $this->getText('Add URI');
		$addPage->desc = $this->getText('Add Desc');
		$this->addPage($addPage, false);
		
		$uriTF = $this->newTextField('uri-tf');
		$uriTF->text = 'URI';
		$uriTF->desc = $this->getText('URI Desc');
		$addPage->addChild($uriTF);
		
		$templateTf = $this->newTextField('template-tf');
		$templateTf->text = 'Template';
		$templateTf->desc = $this->getText('Template Desc');
		$addPage->addChild($templateTf);
		
		$addOKBtn = $this->newButton('add-uri-ok-btn');
		$addOKBtn->text = $this->getText('Ok');
		$addOKBtn->action('url.add', '#uri-tf', '#template-tf');
		$addOKBtn->redirect('url.uris-page');
		$addPage->addChild($addOKBtn);
		
		$editPage = $this->newPage('edit-uri-page');
		$editPage->text = $this->getText('Edit URI');
		$editPage->desc = $this->getText('Add Desc');
		$this->addPage($editPage, false);
		
		$editUriTF = $this->newTextField('edit-uri-tf');
		$editUriTF->text = $this->getText('URI');
		$editUriTF->linkData($this->url->select('url.uri',$this->rowid));
		$editUriTF->desc = $this->getText('URI Desc');
		$editUriTF->action('url.update','url.uri', $this->rowid, '#edit-uri-tf');
		$editPage->addChild($editUriTF);
		
		$editTempTF = $this->newTextField('edit-template-tf');
		$editTempTF->text = $this->getText('Template');
		$editTempTF->linkData($this->url->select('url.template',$this->rowid));
		$editTempTF->desc = $this->getText('Template Desc');
		$editTempTF->action('url.update','url.template', $this->rowid, '#edit-template-tf');
		$editPage->addChild($editTempTF);
				
		$editOKBtn = $this->newButton('edit-ok-btn');
		$editOKBtn->text = $this->getText('Ok');
		$editOKBtn->redirect('url.uris-page');
		$editPage->addChild($editOKBtn);
		
		$backBtn = $this->newButton('edit-back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('url.uris-page');
		$editPage->addChild($backBtn);
		
	}
	
}

?>