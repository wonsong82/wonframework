<?php
namespace app\module;
class MailAdmin extends \app\engine\AdminView{
	
	public function construct(){
		
		// load configs
		$config = $this->mail->getConfig();
		
		$spage = $this->newPage('sender-page');
		$spage->text = $this->getText('Mail Sender Setting');
		$spage->desc = $this->getText('Set up your emailing method.');
		$this->addPage($spage);
		
		$options = array(
			'PHP Mail'=>0,
			'SMTP'=>1
		);
		$selected = $config['is_smtp']? 1 : 0;		
		$method = $this->newSelect('method');
		$method->text = $this->getText('Method');
		$method->linkData($options, $selected);
		$method->css = 'margin-bottom:20px';
		$method->action('mail.update', 'mail.is_smtp, #method');
		$method->redirect('mail.sender-page');
		$spage->addChild($method);
						
		$hosttf = $this->newTextField('host');
		$hosttf->text = $this->getText('Host Name');
		$hosttf->enabled = $config['is_smtp']? true:false;
		$hosttf->linkData($config['host']);
		$hosttf->action('mail.update', 'mail.host, #host');
		$spage->addChild($hosttf);
		
		$porttf = $this->newTextField('port');
		$porttf->text = $this->getText('Port');
		$porttf->linkData($config['port']);
		$porttf->action('mail.update', 'mail.port, #port');
		$porttf->css = 'margin-bottom:20px;width:50px;';
		$spage->addChild($porttf);
		
		$authcb = $this->newCheckBox('isauth');
		$authcb->text = $this->getText('SMTP Auth');
		$authcb->enabled = $config['is_smtp'] && $config['is_smtp']? true:false;
		$authcb->linkData($config['is_auth']);
		$authcb->action('mail.update', 'mail.is_auth, #isauth');
		$authcb->redirect('mail.sender-page');
		$spage->addChild($authcb);
		
		$authuser = $this->newTextField('auth-user');
		$authuser->text = $this->getText('Username');
		$authuser->enabled = $config['is_smtp'] && $config['is_auth']? true:false;
		$authuser->linkData($config['auth_user']);
		$authuser->action('mail.update', 'mail.auth_user, #auth-user');
		$spage->addChild($authuser);
		
		$authpass = $this->newTextField('auth-pass');
		$authpass->text = $this->getText('Password');
		$authpass->enabled = $config['is_smtp'] && $config['is_auth']? true:false;
		$authpass->linkData($config['auth_pass']);
		$authpass->action('mail.update', 'mail.auth_pass, #auth-pass');
		$spage->addChild($authpass);
		
		$options = array(
			'None'=>'',
			'SSL'=>'ssl',
			'TLS'=>'tls'
		);
		if($config['secure']=='') $selected=0;
		if($config['secure']=='ssl') $selected=1;
		if($config['secure']=='tls') $selected=2;
		
		$secure = $this->newSelect('secure');
		$secure->text = $this->getText('Security');
		$secure->enabled = $config['is_smtp'] && $config['is_smtp']? true:false;
		$secure->linkData($options, $selected);
		$secure->css = 'margin:20px 0px';
		$secure->action('mail.update', 'mail.secure, #secure');
		$secure->redirect('mail.sender-page');
		$spage->addChild($secure);
		
		$google = $this->newButton('google-preset');
		$google->text = $this->getText('Load Gmail SMTP Preset');
		$google->action('mail.gmailPreset');
		$google->redirect('mail.sender-page');
		$spage->addChild($google);
		
		$default = $this->newButton('default-preset');
		$default->text = $this->getText('Load Default Mail Preset');
		$default->action('mail.defaultPreset');
		$default->redirect('mail.sender-page');
		$spage->addChild($default);
		
			
	}
	
}

?>
