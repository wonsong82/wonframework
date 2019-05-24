<div id="main" class="<?=strtolower(preg_replace('#([^A-Z-])([A-Z])#', '$1-$2', Won::get('Config')->this_module));?>" module="<?=Won::get('Config')->this_module;?>">

	<h1 class="title"><?=Won::get('Config')->this_module;?></h1>

    <div id="content">
        <div id="subnav">
            <ul>           
                <?php foreach ($admin_pages as $admin_page) { ?>
                <li<?=(Won::get('Config')->this_module_page==$admin_page)? ' class="selected"' : '';?>>
                	<a href="<?=Won::get('Config')->admin_url . '/' . Won::get('Config')->this_module . '/' . $admin_page?>"><?=ucwords(str_replace('_', ' ', $admin_page))?></a>
                </li>
                <?php } ?>
                
            </ul>
        </div>
        
        <div id="page">
        	<table><tr><td>
            	<h2><?=Won::get('Config')->this_module?> > <?=ucwords(str_replace('_', ' ', Won::get('Config')->this_module_page))?></h2>
            </td></tr></table>
            <?php require Won::get('Config')->this_module_page_file;?>
        </div>
    </div>

</div> 
   
<div id="msg"></div>    
