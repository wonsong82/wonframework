<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$this->Permalink->title?></title>

<!-- jquery -->
<script src="<?=$this->Config->site_url?>/lib/jquery/jquery-1.7.1.min.js"></script>
<script src="<?=$this->Config->site_url?>/lib/jquery/jquery.effects.js"></script>
<script src="<?=$this->Config->site_url?>/lib/jquery.loadImages/jquery.loadImages.1.0.1.min.js"></script>
<script src="<?=$this->Config->site_url?>/lib/cufon/cufon-yui.js"></script>
<script src="<?=$this->Config->site_url?>/lib/cufon/agaramondpro-i.js"></script>
<script src="<?=$this->Config->site_url?>/lib/cufon/dinpro-r-b.js"></script>
<script src="<?=$this->Config->site_url?>/lib/cufon/style.cufon.js"></script>
<script src="<?=$this->Config->site_url?>/lib/jquery.jscrollpane/jquery.mousewheel.js"></script>
<script src="<?=$this->Config->site_url?>/lib/jquery.jscrollpane/jquery.jscrollpane.min.js"></script>
<script src="<?=$this->Config->site_url?>/lib/flarevideo/javascripts/jquery.ui.slider.js"></script>
<script src="<?=$this->Config->site_url?>/lib/flarevideo/javascripts/jquery.flash.js"></script>
<script src="<?=$this->Config->site_url?>/lib/flarevideo/javascripts/flarevideo.js"></script>  
<script src="<?=$this->Config->content_url?>/script.js"></script>
<link rel="stylesheet" href="<?=$this->Config->site_url?>/lib/flarevideo/stylesheets/flarevideo.css" type="text/css">
<link rel="stylesheet" href="<?=$this->Config->site_url?>/lib/flarevideo/stylesheets/flarevideo.vimeo.css" type="text/css">
<link rel="stylesheet" href="<?=$this->Config->site_url?>/lib/jquery.jscrollpane/jquery.jscrollpane.css" type="text/css">
<link rel="stylesheet" href="<?=$this->Config->content_url?>/style.css" />

<!-- favicon -->

<!--[if !IE 7]>
<style type="text/css">
#wrap {display:table;height:100%}
</style>
<![endif]-->
</head>

<body>

<div id="band"></div>
<div id="wrap">
    
    <div id="header">
    	
        <div id="logo">
        	<a title="go home" href="<?=$this->Config->site_url?>"><img src="<?=$this->Config->content_url?>/img/logo.png" /></a>
        </div><!--#logo-->
        
        <!-- NAV -->
<?php $parent = explode('/',$this->Permalink->uri);$parent = $parent[0];?>
        <div id="nav">
            <a href="<?=$this->Config->site_url?>" class="din<?=$parent==''?' current':''?>">HOME</a>
            <a href="<?=$this->Config->site_url?>/about" class="din<?=$parent=='about'?' current':''?>">ABOUT US</a>
            <a href="<?=$this->Config->site_url?>/products" class="din<?=$parent=='products'?' current':''?>">PRODUCTS</a>
            <a href="<?=$this->Config->site_url?>/news" class="din<?=$parent=='news'?' current':''?>">NEWS & EVENTS</a>
            <a href="<?=$this->Config->site_url?>/contact" class="din<?=$parent=='contact'?' current':''?>">CONTACT US</a>              
        </div>
        <!-- #NAV -->
        
        <div id="multilang">
        	<a href="<?=$this->Config->site_url?>" class="selected">English</a>
            <a href="javascript:alert('coming soon');">中文</a>
            </ul>
        </div>
            
    </div><!--#header-->