<?php
$elements = $this->Contents->get_elements_by_uri($this->Permalink->uri);
function parseElement($c) {
	$html = '';
	// text
	if ($c['type']=='text') {
			$html .= "<h3>{$c['title']}</h3>";
			$html .= "<p>{$c['value']}</p>";
		}
	// image       	
	if ($c['type']=='image') {
		$html .= '<img src="'.$c['value']['img'].'" alt="'.$c['title'].'" title="'.$c['title'].'"/>';
	}	
	// link
	if ($c['type']=='link') {
		$html .= '<p><a href="'.Won::get('Config')->site_url.'/'.$c['value']['href'].'">'.$c['value']['text'].'</a></p>';
	}	
	// group 
	if ($c['type']=='group') {
		foreach ($c['elements'] as $subE) {
			$html .= parseElement($subE);
		}
	}
	
	return $html;
}

include $this->Config->content_dir.'/header.php';
?>

<div id="main" class="legal-privacy">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">LEGAL & PRIVACY</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="current">
                	<a class="din" href="<?=$this->Config->site_url?>/legal-statement">Legal</a>
                </li>
                <li>
                	<a class="din" href="<?=$this->Config->site_url?>/privacy-policy">Privacy</a>
               
            </ul>
        </div>
        
        <div id="page">
        
        	<h1>LEGAL STATEMENT</h1>
            
        	<?php foreach ($elements as $c) {
            	echo parseElement($c);	
			} ?>
        </div>		
    </div>

</div><!--#main-->   
<?php include $this->Config->content_dir.'/footer.php'?>