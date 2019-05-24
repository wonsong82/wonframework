<?php
require_once '../../config.php';
$phpdoc = new PHPDoc();
?>
<div class="help">
	<a onclick="toggle_help(this)"><h1><?=$_POST['class']?> Class Usage Help <span class="sign">▶</span></h1></a>
    <div><?=$phpdoc->public_document($_POST['class'])?></div>
</div>
