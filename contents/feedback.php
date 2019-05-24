<?php
include $this->Config->content_dir.'/header.php';

$elements = $this->Contents->get_elements_by_uri($this->Permalink->uri);
function parseElement($c) {
	$html = '';
	// text
	if ($c['type']=='text') {
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

<div id="main" class="feedback">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">CONTACT US</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li>
                	<a class="din" href="<?=$this->Config->site_url?>/contact">Contact Us</a>
                </li>
                <li class="current">
                	<a class="din" href="<?=$this->Config->site_url?>/feedback">Comments /Feedback</a>
                </li>
            </ul>
        </div>
        
        <div id="page">
        	
            <!-- text -->
			<?php foreach ($elements as $c) {
            	echo parseElement($c);
			} ?>           
              
            <h4>+Add your comment</h4><br/>
            <div id="feedback-form-div">
            
            <form id="feedback-form" action="<?=$this->Config->site_url?>/feedback-submit" method="post">
            	<label for="f-name">Name:</label><input name="f-name" id="f-name" type="text" /><br/>
                <label for="f-feedback">Comments/<br/>Feedback:</label><textarea rows="7" name="f-feedback" id="f-feedback"></textarea>
                <input type="hidden" id="f-display" name="f-display" value="no"/>
                <input type="submit" value="submit" />  
                <div id="f-msgbox"></div>
                         	
            </form>
            </div><!--#feed-form-div-->
            
            <div id="feedbacks">
            	<ul>
                	<?php foreach ($this->Forms->getRecords('Feedback Form') as $record) { if ($record['values']['Display']==true) { ?>
                    <li>
                    	<h4><?=$record['values']['Name']?></h4>
                        <p><?=nl2br($record['values']['Feedback'])?></p>
                        <span class="time"><?=date('m/d/Y',strtotime($record['date']))?></span>
                    </li>
                    <?php }} ?>
                </ul>
            </div>
            <div id="more-feedback"><a href="#">> View More</a></div>
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