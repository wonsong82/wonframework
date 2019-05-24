<!DOCTYPE html>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="<?=Won::get('Config')->admin_content_url?>/js/jquery-1.7.1.min.js"></script>
    <script src="<?=Won::get('Config')->admin_content_url?>/js/jquery-ui-1.8.17.custom.min.js"></script> 
    <script src="<?=Won::get('Config')->admin_content_url?>/js/jquery.ui.nestedSortable.js"></script>
    <script src="<?=Won::get('Config')->admin_content_url?>/js/jquery.Jcrop.min.js"></script>
    
    <script src="<?=Won::get('Config')->admin_content_url;?>/js/jquery.mousewheel.js"></script>
    <script src="<?=Won::get('Config')->admin_content_url;?>/js/jquery.jscrollpane.min.js"></script>
    
    <script src="<?=Won::get('Config')->admin_content_url;?>/js/jquery.autogrow.js"></script>
    
	<script>var module_url = "<?=Won::get('Config')->module_url?>";
    var site_url = '<?=Won::get('Config')->site_url?>';</script>   
    
    <script src="<?=Won::get('Config')->admin_content_url?>/js/script.js" ></script>
    
    
    <link rel="stylesheet" href="<?=Won::get('Config')->admin_content_url?>/css/ui-lightness/jquery-ui-1.8.17.custom.css" />
    <link rel="stylesheet" href="<?=Won::get('Config')->admin_content_url?>/css/style.css" />
    <link rel="stylesheet" href="<?=Won::get('Config')->admin_content_url?>/css/jquery.Jcrop.css" />
    <link rel="stylesheet" href="<?=Won::get('Config')->admin_content_url;?>/css/jquery.jscrollpane.css" />
</head>

<body>
	<div id="header">
        <div id="brand">
        <?php Won::set(new Settings());
		$logo = Won::get('Settings')->getSetting('Core','siteLogo');
		$head = $logo==true? '<img src="'.Won::get('Config')->content_url.'/uploads/'.$logo.'" height="50"/>' : 'WEBWON CMS';
		?>
        <?=$head?>
        </div>        
        <div id="greet">Hi, <b><?=Won::get('User')->name?></b>, Today is <?=date('M jS, Y l')?> <a href="<?=Won::get('Config')->admin_url?>/logout">Log out</a></div>
        <div id="nav">
        	<ul>
            	<?php foreach ($modules as $module) { ?>
                <li<?=$module==Won::get('Permalink')->params['module']? ' class="current"' : '';?>>
                	<a href="<?=Won::get('Config')->admin_url?>/<?=$module?>"><?=$module?></a>
                </li>                
                <?php } ?>
                
            </ul>
		</div>
  	</div>
    
    <div id="wrapper">