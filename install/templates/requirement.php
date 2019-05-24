<?php
/**
 * @package Installer/templates
 * @name welcome.php
 * @desc Requirement check
 * @author Won Song
 */
 
 
// check for the progress
if ($_POST['progress'] < 1)
exit();


require '../includes/Server.php';
$server = new Server();
$php_version = $server->check_php_version();
$mysql_version = $server->check_mysql_version();
$php_check = $server->check_php();

$enabled = $php_version['status'] && $php_check['status'] && $mysql_version['status'] ? true : false;
$disabled_tag = $enabled? '' : ' class="disabled"';	
?>


<h1>Requirement Check</h1>
    
<table id="php-version">    	
    <thead>
        <td colspan="2" align="center">PHP Version</td>
    </thead>
    
    <tr>
        <td width="200">PHP Version</td>
        <td><?=$php_version['status']? '<h1>Ok</h1>' : '<h3 class="error">X</h3>'?></td>            
    </tr>
    
    <?php if (!$php_version['data'][0]['status']) { ?>
    <tr>
        <td colspan="2" class="alert-noicon">└ <?=$php_version['data'][0]['error_msg']?></td>
    </tr>      
    <?php } ?> 
</table>

 <table id="mysql_version">    	
    <thead>
        <td colspan="2" align="center">MySQL Version</td>
    </thead>
    
    <tr>
        <td width="200">MySQL Version</td>
        <td><?=$mysql_version['status']? '<h1>Ok</h1>' : '<h3 class="error">X</h3>'?></td>            
    </tr>
    
    <?php if (!$mysql_version['data'][0]['status']) { ?>
    <tr>
        <td colspan="2" class="alert-noicon">└ <?=$mysql_version['data'][0]['error_msg']?></td>
    </tr>
    <?php } ?>        
</table>


<table id="php-check">    	
    <thead>
        <td colspan="2" align="center">PHP Settings &amp; Modules</td>
    </thead>
    
    <?php foreach ($php_check['data'] as $module) { ?>
    <tr>
        <td width="200"><?=$module['name']?></td>
        <td><?=$module['status']? '<h1>Ok</h1>' : '<h3 class="error">X</h3>'?></td>            
    </tr>
    
    <?php if (!$module['status']) { ?>
    <tr>
        <td colspan="2" class="alert-noicon">└ <?=$module['error_msg']?></td>
    </tr>
    <?php } }?>        
    
    <tr>    	
    	<td colspan="2" align="right"><input<?=$disabled_tag?> type="button" value="Next >>"<?php if($enabled) {?> next="dbsetup" onclick="changeHash(this)"<?php }?> progress="2"/></td>
    </tr>
    
</table>