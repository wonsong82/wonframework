<?php
namespace app\module;
class UserAdmin extends \app\engine\AdminView{
	
	public function construct(){
		
		$userPage = $this->newPage('users-page');
		$userPage->text = $this->getText('Users');
		$this->addPage($userPage);	
		
		$userAddBtn = $this->newButton('add-user-btn');
		$userAddBtn->text = $this->getText('Add User');
		$userAddBtn->redirect('user.add-user-page');
		$userPage->addChild($userAddBtn);
		
		$userTable = $this->newTable('users-list');
		$userTable->linkData($this->user->getUsers());
		$userTable->order('user', 'user');
		$userPage->addChild($userTable);		
		
		$userEditBtn = $this->newButton('edit-user-btn');
		$userEditBtn->text = $this->getText('Edit');
		$userEditBtn->redirect('user.edit-user-page', 'parent.parent.rowid');
		$userTable->addChild($userEditBtn);
		
		$userRemoveBtn = $this->newButton('remove-user-btn');
		$userRemoveBtn->text = $this->getText('Remove');
		$userRemoveBtn->action('user.removeUser', 'parent.parent.rowid');
		$userRemoveBtn->redirect('user.users-page');
		$userTable->addChild($userRemoveBtn);
		
		// Add User Page
		$addUserPage = $this->newPage('add-user-page');
		$addUserPage->text = $this->getText('Add User');
		$this->addPage($addUserPage, false);
		
		$usernameTF = $this->newTextField('username-tf');
		$usernameTF->text = $this->getText('username');
		$addUserPage->addChild($usernameTF);
				
		$passwordTF = $this->newTextField('password-tf');
		$passwordTF->text = $this->getText('password');
		$passwordTF->password = true;
		$addUserPage->addChild($passwordTF);
		
		$addUserOKBtn = $this->newButton('add-user-ok-btn');
		$addUserOKBtn->text = $this->getText('Ok');
		$addUserOKBtn->action('user.addUser', '#username-tf,#password-tf');
		$addUserOKBtn->redirect('user.users-page');
		$addUserPage->addChild($addUserOKBtn);
		
		$backBtn = $this->newButton('add-user-back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('user.users-page');
		$addUserPage->addChild($backBtn);
		
		// Edit User Page		
		$editUserPage = $this->newPage('edit-user-page');
		$editUserPage->text = $this->getText('Edit User');
		$this->addPage($editUserPage, false);
		
		$userNameTF = $this->newTextField('username');
		$userNameTF->text = $this->getText('Username');
		$userNameTF->linkData($this->user->select('user.username', $this->rowid));
		$userNameTF->static = true;
		$editUserPage->addChild($userNameTF);
		
		$newPasswordTF = $this->newTextField('new-password');
		$newPasswordTF->text = $this->getText('Change Password');
		$editUserPage->addChild($newPasswordTF);
		
		$newPasswordBtn = $this->newButton('new-password-btn');
		$newPasswordBtn->text = $this->getText('Update New Password');
		$newPasswordBtn->action('user.update', 'user.password,parent.rowid,#new-password');
		$newPasswordBtn->redirect('user.edit-user-page', 'parent.rowid');
		$editUserPage->addChild($newPasswordBtn);
		
		$joinedTF = $this->newTextField('joined');
		$joinedTF->text = $this->getText('Joined');
		$joinedTF->linkData($this->user->select('user.joined', $this->rowid));
		$joinedTF->static = true;
		$editUserPage->addChild($joinedTF);
		
		$activeCheck = $this->newCheckBox('active');
		$activeCheck->text = $this->getText('Active');
		$activeCheck->action('user.update','user.active,parent.parent.rowid,#active');
		$activeCheck->linkData($this->user->select('user.active',$this->rowid));
		$editUserPage->addChild($activeCheck);
		
		$banCheck = $this->newCheckBox('banned');
		$banCheck->text = $this->getText('Banned');
		$banCheck->linkData($this->user->select('user.banned',$this->rowid));
		$banCheck->action('user.update','user.banned,parent.parent.rowid,#banned');
		$editUserPage->addChild($banCheck);
		
		$editOKBtn = $this->newButton('edit-ok-btn');
		$editOKBtn->text = $this->getText('Ok');
		$editOKBtn->redirect('user.users-page');
		$editUserPage->addChild($editOKBtn);
		
		$backBtn = $this->newButton('edit-back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('user.users-page');
		$editUserPage->addChild($backBtn);		
				
		
		$groupSection = $this->newPage('groups-section');
		$groupSection->text = $this->getText('Groups');
		$editUserPage->addChild($groupSection);
				
		foreach($this->user->getGroups() as $group){
			$groupCheck = $this->newCheckBox('group-'.$group['id']);
			$groupCheck->text = $group['name'];
			$groupCheck->linkData($this->user->isUserMemberOf($this->rowid,$group['id']));
			$groupCheck->action('user.updateGroup',"{$this->rowid},{$group['id']},#group-{$group['id']}");
			$groupSection->addChild($groupCheck);
		}
		
		// Group Page
		$groupPage = $this->newPage('groups-page');
		$groupPage->text = $this->getText('Groups');
		$this->addPage($groupPage);
		
		$groupAddBtn = $this->newButton('add-group-btn');
		$groupAddBtn->text = $this->getText('Add Group');
		$groupAddBtn->redirect('user.add-group-page');
		$groupPage->addChild($groupAddBtn);
		
		$groupTable = $this->newTable('groups-list');
		$groupTable->linkData($this->user->getGroups());
		$groupTable->order('user', 'usergroup');
		$groupPage->addChild($groupTable);		
		
		$groupEditBtn = $this->newButton('edit-group-btn');
		$groupEditBtn->text = $this->getText('Edit');
		$groupEditBtn->redirect('user.edit-group-page', 'parent.parent.rowid');
		$groupTable->addChild($groupEditBtn);
		
		$groupRemoveBtn = $this->newButton('remove-group-btn');
		$groupRemoveBtn->text = $this->getText('Remove');
		$groupRemoveBtn->action('user.removeGroup', 'parent.parent.rowid');
		$groupRemoveBtn->redirect('user.groups-page');
		$groupTable->addChild($groupRemoveBtn);
		
		// Add Group Page
		$addGroupPage = $this->newPage('add-group-page');
		$addGroupPage->text = $this->getText('Add Group');
		$this->addPage($addGroupPage, false);
		
		$groupnameTF = $this->newTextField('groupname-tf');
		$groupnameTF->text = $this->getText('Group Name');
		$addGroupPage->addChild($groupnameTF);
			
		$addGroupOKBtn = $this->newButton('add-group-ok-btn');
		$addGroupOKBtn->text = $this->getText('OK');
		$addGroupOKBtn->action('user.addGroup', '#groupname-tf');
		$addGroupOKBtn->redirect('user.groups-page');
		$addGroupPage->addChild($addGroupOKBtn);
		
		$backBtn = $this->newButton('add-group-back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('user.groups-page');
		$addGroupPage->addChild($backBtn);
		
		// Edit Group Page
		$editGroupPage = $this->newPage('edit-group-page');
		$editGroupPage->text = $this->getText('Edit Group');
		$this->addPage($editGroupPage, false);
		
		$groupNameTf = $this->newTextField('group-name-tf');
		$groupNameTf->text = $this->getText('Group Name');
		$groupNameTf->linkData($this->user->select('usergroup.name',$this->rowid));
		$groupNameTf->action('user.update', 'usergroup.name,parent.parent.rowid,#group-name-tf');
		$editGroupPage->addChild($groupNameTf);
		
		$editOKBtn = $this->newButton('edit-ok-btn');
		$editOKBtn->text = $this->getText('OK');
		$editOKBtn->redirect('user.groups-page');
		$editGroupPage->addChild($editOKBtn);
		
		$backBtn = $this->newButton('edit-back-btn');
		$backBtn->text = $this->getText('Back');
		$backBtn->redirect('user.groups-page');
		$editGroupPage->addChild($backBtn);		
		
		if(isset($this->args['page'])&&$this->args['page']=='edit-group-page'){
			$group=$this->user->getGroup($this->rowid);							
			if(!$group['editable'])
				$groupNameTf->static = true;
		}
		
	}
	
}

?>
