<?php
/*
function get_banners()
{
	$banner = new Banner();
	$banners = $banner->get_banners();
	
	$html = '<div id="banner"><ul>';
	foreach ($banners as $banner) 
	{
		$html .= '<li bannerid="'.$banner['id'].'">';
		$html .= '<h1 class="title din-shadow">'.$banner['title'].'</h1>';
		
		$html .= '<div class="banner">';
		if ($banner['link'])
			$html .= '<a href="'.$banner['link'].'">';
			 
		$html .= '<img src="'.$banner['imgpath'].'" />';
		
		if ($banner['link'])
			$html .= '</a>';
		$html .= '</div>';
		
		$html .= '</li>';	
	}
	$html .= '</ul></div>';
	
	return $html;	
}*/

function get_banners()
{
	$banner = new Banner();
	$banners = $banner->get_banners();
	
	$html = '<ul>';
	foreach ($banners as $banner) {
		$html .= '<li bannerid="'.$banner['id'].'">';
		$html .= '<a href="'.$banner['link'].'">';
		$html .= '<img src="'.$banner['imgpath'].'" />';
		$html .= '</a>';
		$html .= '</li>';		
	}
	$html .= '</ul>';
	
	return $html;
}



function get_navs()
{
	$navs = array(
		'HOME'=>'',
		'OUR COMPANY'=>'company',
			
		'PRODUCTS'		
	);
	$nav = '<ul>';
	
	
	$nav = '</ul>';
}

function get_home_imgmenu() { ?>
	<ul>
    	<?php foreach ($this->Contents->get_elements_by_uri('home') as $e) { ?>
        <li>
        	<div class="img">
            	
        	<h3><?=$e['title']?></h3>
            
        </li>  		      
		<?php } ?>
        </ul>
<?php 
	}

?>