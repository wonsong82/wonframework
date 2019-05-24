<?php
$banners = array(
	$this->config->content.'/banners/1.jpg',
	$this->config->content.'/banners/2.jpg',
	$this->config->content.'/banners/3.jpg',
	$this->config->content.'/banners/4.jpg',
	$this->config->content.'/banners/5.jpg'
);

$this->lib->import('external.facebook');

?>

<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Karaoke NYC, Karaoke Bars NYC, Bars in NYC, Sing Sing Karaoke, Bayside Karaoke, Karaoke Queens NY" />
	<title>Karaoke Sing Sing</title>
    <link rel="stylesheet" type="text/css" href="<?=$this->config->content.'fonts/css.php'?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$this->config->content.'css/jquery-ui-1.8.17.custom.css'?>"/>
    <link rel="stylesheet" type="text/css" href="<?=$this->config->content.'css/screen.css'?>"/>    
    <script src="<?=$this->config->script.'jquery/jquery-1.7.1.min.js'?>"></script>
    <script src="<?=$this->config->script.'jqueryui/jquery-ui-1.8.17.custom.min.js'?>"></script>
    
</head>

<body class="home">
	
    <div id="header">
    	<div id="logo">
        	<img src="<?=$this->config->content.'/css/images/logo.png'?>" />
        </div>
        <div id="nav">
        	<ul>
            	<li><a href="#" class="selected">HOME</a></li>
                <li><a href="#">RESERVATION</a></li>
                <li><a href="#">SONG LIST</a></li>
                <li><a href="#">PRICES</a></li>
                <li><a href="#">PHOTOS</a></li>
                <li><a href="#">FAQ</a></li>
                
            </ul>
        </div>
    </div><!--#header-->
    
    <!--#BANNER START-->
    <div class="banner-wrap">
    	<div class="banner-wrap-left"></div><div class="banner-wrap-right"></div>
        <div id="banner">
        	<ul>
            <? foreach ($banners as $banner):?>
            	<li><img src="<?=$banner?>"/></li>
            <? endforeach;?>
            </ul>
        </div>
	</div>
    <script src="<?=$this->config->script?>jquery.loadImages/jquery.loadImages.1.0.1.min.js"></script>
    <script src="<?=$this->config->script?>sliders/jquery.slider.js"></script>
    <script>
		$('#banner').slider({'init':true,type:'box'});
	</script>
    <!--#BANNER END-->
	
    <div id="page">
    	
    </div>
	
</body>
</html>