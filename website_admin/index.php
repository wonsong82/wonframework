<?php
// Logout if set
if(isset($this->req->post['logout']) && $this->req->post['logout']==1){
	$this->user->logout();
}

// Authenticate the User
$this->user->auth();

// If No Authentication
if(!$this->user->logged){
	require $this->config->adminDir . 'template/login.php';
	exit();
}

// If Not Admin, Permission Error
if(!$this->user->isMemberOf('Administrator')){
	require $this->config->adminDir . 'template/login.php';
	$this->user->logout();
	exit();
}

// Set Navigations
$this->admin->addMenu('home', 'Home', 'Administrator');
$this->admin->addMenu('user', 'Users', 'Administrator');
$this->admin->addMenu('url', 'URIs', 'Administrator');
$this->admin->addMenu('content', 'Contents', 'Administrator');
$this->admin->addMenu('gallery', 'Gallery', 'Administrator');
$this->admin->addMenu('event', 'Events', 'Administrator');
//$this->admin->addMenu('image', 'Images', 'Administrator');
$this->admin->addMenu('mail', 'Mail', 'Administrator');
$this->admin->addMenu('store', 'Store', 'Administrator');

// Parse current Page
$this->admin->parseMenu();

// Display templates
include $this->config->adminDir. 'template/header.php';
include $this->config->adminDir. 'template/body.php';
include $this->config->adminDir. 'template/footer.php';
?>