<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Webwon Admin</title>
<link rel="stylesheet" type="text/css" media="all" href="<?=$this->config->adminContent?>css/screen.css"/>
<script type="text/javascript" src="<?=$this->config->adminContent?>js/screen.js"></script>
</head>

<body>
	<div id="header">
    	<div id="lang">
        	<? foreach($this->lang->langs as $code=>$text):?>
            <a <?=$this->lang->lang==$code?'class="selected" ':''?>href="<?=$this->url->getLangUrl($code);?>"><?=$text;?></a>
			<? endforeach;?>            
        </div><!--#lang-->
        <div id="login">
        	<form action="<?=$this->url->url?>" method="post">
            	<input type="hidden" name="logout" value="1" />
            	<input type="submit" value="Logout" />
            </form>
        </div><!--#login-->
        
        <div id="nav">        
    		<? include $this->config->adminDir. 'template/nav.php';?>
        </div><!--#nav-->
    </div><!--#header-->