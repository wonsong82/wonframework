<?php 
include $this->Config->content_dir.'/header.php'; 
$elements = $this->Contents->get_elements_by_uri($this->Permalink->uri);
function parseElement($c) {
	$html = '';
	// text
	if ($c['type']=='text') {
		if ($c['title']=='Title')
			$html .= "<h2 class=\"din\">{$c['value']}</h1>";
		else {
			$html .= "<h3>{$c['title']}</h3>";
			$html .= "<p>{$c['value']}</p>";			
		}}
	// image       	
	if ($c['type']=='image') {
		$html .= '<img src="'.$c['value']['path'].'/'.$c['value']['img'].'" alt="'.$c['title'].'" title="'.$c['title'].'"/>';
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
        <?php $sub = $this->Permalink->params['page'];?>
        	<ul>
            	<li>
                	<a class="din" href="<?=$this->Config->site_url?>/about">Main</a>
                </li>
                <li>
                	<a class="din" href="<?=$this->Config->site_url?>/about/vision">Vision &amp;<br/>Mission</a>
                </li>
                <li>
                	<a class="din" href="<?=$this->Config->site_url?>/about/partners">Our Partners</a>
                </li>
                <li class="current">
                	<a class="din" href="<?=$this->Config->site_url?>/about/career-opportunity">Career <br/>Opportunity</a>
                </li>
            </ul>
        </div>
        
        <div id="page">
        	<h1 class="din">CAREER OPPORTUNITY</h1>
        	<?php if (count($elements)) { foreach ($elements as $c) {
            	echo parseElement($c);	
			}} else { ?>
            	<h2>The position is currently closed for hiring</h2>
            <?php } ?>
            <br/>
            <a class="back" href="<?=$this->Config->site_url?>/about/career-opportunity">Back to Career Opportunity</a>
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

