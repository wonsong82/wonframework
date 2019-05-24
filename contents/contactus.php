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
$contacts = $this->Contact->getContacts();
?>

<div id="main" class="contact">

    <div id="banner-con">
        <div id="hor-banner"><h1 class="din">CONTACT US</h1></div>
    </div><!--#banner-con-->

    <div id="content">
        
        <div id="submenu">
        	<ul>
            	<li class="current">
                	<a class="din" href="<?=$this->Config->site_url?>/contact">Contact Us</a>
                </li>
                <li>
                	<a class="din" href="<?=$this->Config->site_url?>/feedback">Comments /Feedback</a>
                </li>
            </ul>
        </div>
        
        <div id="page">
        	
            <!-- text -->
			<?php foreach ($elements as $c) {
            	echo parseElement($c);	
			} ?>
            
            <div id="contact-info">
                <div class="nz location-info">
                    <h4><?=$contacts[0]['name']?></h4>
                    <span class="company-address"><?=nl2br($contacts[0]['address'])?></span><br/>
                    <strong>Tel: </strong><span class="phone-number"><?=$contacts[0]['phone']?></span><br/>
                    <strong>Fax: </strong><span class="fax-number"><?=$contacts[0]['fax']?></span>
                </div>
                
                <div class="cn location-info">
                    <h4><?=$contacts[1]['name']?></h4>
                    <span class="company-address"><?=nl2br($contacts[1]['address'])?></span><br/>
                    <strong>Tel: </strong><span class="phone-number"><?=$contacts[1]['phone']?></span><br/>
                    <strong>Fax: </strong><span class="fax-number"><?=$contacts[1]['fax']?></span>
                </div>
            </div><!--#contact-info-->
            
            <div id="contact-us">
            <form id="contact-us-form" action="<?=$this->Config->site_url?>/contact-submit" method="post">
            	<label for="c-name">Name:</label><input name="c-name" id="c-name" type="text" /><br/>
                <label for="c-email">E-mail:</label><input name="c-email" id="c-email" type="text"/><br/>
                <label for="c-phone">Phone:</label><input name="c-phone" id="c-phone" type="text"/><br/>
                <label for="c-subject">Subject:</label><input name="c-subject" id="c-subject" type="text" /><br/>
                <label for="c-message">Message:</label><textarea rows="7" name="c-message" id="c-message"></textarea><br/>
                <span>Subscribe for promotions updates from Oravida:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><input type="radio" name="c-subscribe" value="yes" checked="checked" /> <span>YES</span> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="c-subscribe" value="no" /> <span>NO</span><br/>
                <div id="c-msgbox"></div>
            	
                <input type="submit" value="submit" />
            </form>
            </div>
            
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

