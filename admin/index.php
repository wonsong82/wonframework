<?php
if (!file_exists('../config.php'))
	header('location:../install');

require '../config.php';

$page = new Permalink();

// simple password
SimplePassword::set('admin', ADMIN_PASS, true);

// get module admin menus
$modules = array();
$module_dir = opendir(MODULE_DIR);

// if modules/modulename/module.php and admin_menu.php exists
while (false !== ($module_name = readdir($module_dir)))
{
	$module_class = file_exists(MODULE_DIR . '/' . $module_name . '/' . $module_name . '.php');
	$module_admin = file_exists(MODULE_DIR . '/' . $module_name . '/admin/' . 'admin.php');
	
	if ($module_class && $module_admin)
		$modules[] = $module_name;		
}

// nav
$nav = '<ul>';
foreach ($modules as $module)
	$nav .= '<li><a href="' . $module . '">' . $module . '</a></li>';
$nav .= '</ul>';

?>
<!doctype html>

<html>

<head>
	<link rel="stylesheet" href="style.css" />
	<script src="./scripts/jquery.js"></script>
    <script src="./script.js"></script>
</head>

<body>
	<div id="header">
        <div id="brand">WEBWON CMS</div>        
        <div id="greet">Hi, <b><?=ADMIN_NAME?></b>, Today is <?=date('M jS, Y l')?> <a href="logout.php">Log out</a></div>
        <div id="nav"><?=$nav?></div>
  	</div>
    
    <div id="content" module_dir="<?=MODULE_URL?>">
    </div>
    
    <div id="msg"></div>   
    
</body>

</html>