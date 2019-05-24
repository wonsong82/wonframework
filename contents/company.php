<?php 
include $this->Config->content_dir.'/header.php'; 
 
$elements = $this->Contents->get_elements_by_uri($this->Permalink->uri);
function parseElement($c) {
	$html = '';
	// text
	if ($c['type']=='text') {
		if ($c['title']=='Header')
			$html .= "<h1 class=\"din\">{$c['value']}</h1>";
		elseif ($c['title']=='Subheader')
			$html .= "<p><b>{$c['value']}</b></p>";
		else
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


?>

<div id="main" class="company">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">ABOUT US</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        <?php $sub = $this->Permalink->params['subpage'];?>
        	<ul>
            	<li<?=$sub==''?' class="current"':''?>>
                	<a class="din" href="<?=$this->Config->site_url?>/about">Main</a>
                </li>
                <li<?=$sub=='vision'?' class="current"':''?>>
                	<a class="din" href="<?=$this->Config->site_url?>/about/vision">Vision &amp;<br/>Mission</a>
                </li>
                <li<?=$sub=='partners'?' class="current"':''?>>
                	<a class="din" href="<?=$this->Config->site_url?>/about/partners">Our Partners</a>
                </li>
                <li<?=$sub=='career-opportunity'?' class="current"':''?>>
                	<a class="din" href="<?=$this->Config->site_url?>/about/career-opportunity">Career <br/>Opportunity</a>
                </li>
            </ul>
        </div>
        
        <div id="page">
        	<?php foreach ($elements as $c) {
            	echo parseElement($c);	
			} ?>
        </div>
        
        <div id="side">
        	<div id="side-slogan">
            	<div class="wrap">
        		<h1 class="agaramond">Oravida advocates fresh, natural, nutritional food that's essential for healthier lifestyles.</h1>
                </div>
        	</div>
        </div>        
		
    </div>

</div><!--#main-->    
    
<?php include $this->Config->content_dir.'/footer.php';?>

