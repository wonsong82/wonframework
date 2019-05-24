<?php

$classname = isset($_POST['won_admin_helper_classname']) && $_POST['won_admin_helper_classname']!='' ? $_POST['won_admin_helper_classname'] : Won::get('Config')->this_module;


// Get phpdoc for Help document
$phpdoc = new PHPDoc();

// read public methods & properties
$methods = $phpdoc->get_methods($classname, true);
$properties = $phpdoc->get_properties($classname, true);	


?>
<div id="help">
	 <table>
    	<tr>
        	<td><h2>Public Properties</h2></td>            
        </tr>
        
        <tr>
        	<td>
				<?php foreach ($properties as $item) { ?>
              	<br/>▷ <b><?=$item['type']?> <?=$item['name']?></b><br/>
                <?=$item['desc']?><br/>
                <?php } ?>
        	</td>
        </tr>   
    </table>
    
    <table>
    	<tr>
        	<td><h2>Public Methods</h2></td>            
        </tr>
        <tr>
        	<td>
   		        <?php foreach ($methods as $item) { ?>
    			<br/>▷ <b><?=$item['name']?> : <?=$item['return']?></b><br/>
           		<?=$item['desc']?><br/><br/>
                ┌ Parameters:</b><br/>
                <?=nl2br($item['param']);?><br/>
                <?php } ?>
        	</td>
        </tr>
    </table>    
</div>