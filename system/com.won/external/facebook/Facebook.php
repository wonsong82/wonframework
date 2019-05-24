<?php
class Facebook{
	
	//This is a prerequisite, SDK must be included first
	public $sdk = '//connect.facebook.net/en_US/all.js#xfbml=1';
			
	/* 
	FACEBOOK LIKE BUTTON
	args
		href - the URL to like. The XFBML version defaults to the current page.
		send - specifies whether to include a Send button with the Like button. This only works with the XFBML version.
		layout - there are three options.
			standard - displays social text to the right of the button and friends' profile photos below. Minimum width: 225 pixels. Minimum increases by 40px if action is 'recommend' by and increases by 60px if send is 'true'. Default width: 450 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).
			button_count - displays the total number of likes to the right of the button. Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels.
			box_count - displays the total number of likes above the button. Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.
		show_faces - specifies whether to display profile photos below the button (standard layout only)
		width - the width of the Like button.
		action - the verb to display on the button. Options: 'like', 'recommend'
		font - the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
		colorscheme - the color scheme for the like button. Options: 'light', 'dark'
		ref - a label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_). The ref attribute causes two parameters to be added to the referrer URL when a user clicks a link from a stream story about a Like action:
		fb_ref - the ref parameter
		fb_source - the stream type ('home', 'profile', 'search', 'ticker', 'tickerdialog' or 'other') in which the click occurred and the story type ('oneline' or 'multiline'), concatenated with an underscore.
	*/	
	public function getLikeButton($href,$args=array()){
		$p['data-href']=$href;
		$p['data-send']=isset($args['send'])?$args['send']:'false';
		$p['data-layout']=isset($args['layout'])?$args['layout']:'button_count';
		$p['data-show-faces']=isset($args['faces'])?$args['faces']:'false';
		$p['data-width']=isset($args['width'])?$args['width']:90;
		$p['data-action']=isset($args['action'])?$args['action']:'like';
		$p['data-font']=isset($args['font'])?$args['font']:'arial';
		$p['data-colorscheme']=isset($args['color'])?$args['color']:'light';				
		$ps=array();
		foreach($p as $k=>$v)
			$ps[]=$k.'="'.$v.'"';
		return $this->addScriptOnce('facebook-js-sdk',$this->sdk).
		'<div class="fb-like" '.implode(' ',$ps).'></div>';		
	}
	
	/*
	SEND BUTTON
	args
		href - the URL to send.
	font - the font to display in the button. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	colorscheme - the color scheme for the button. Options: 'light', 'dark'
	ref - a label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_). The ref attribute causes two parameters to be added to the referrer URL when a user clicks a link from a stream story about a Send action:
	fb_ref - the ref parameter
	fb_source - the story type ('message', 'group', 'email') in which the click occurred.
	*/
	public function getSendButton($href,$args=array()){
		$p['data-href']=$href;
		$p['data-font']=isset($args['font'])?$args['font']:'arial';
		$p['data-colorscheme']=isset($args['color'])?$args['color']:'light';
		$ps=array();
		foreach($p as $k=>$v)
			$ps[]=$key.'="'.$v.'"';
		return $this->addScriptOnce('facebook-js-sdk',$this->sdk).
		'<div class="fb-send" '.implode(' ',$ps).'></div>'.$this->jsSDK;	
	}
	
	
	/*
	COMMENTS
	args
		href - the URL for this Comments plugin. News feed stories on Facebook will link to this URL.
		width - the width of the plugin in pixels. Minimum recommended width: 400px.
		colorscheme - the color scheme for the plugin. Options: 'light', 'dark'
		num_posts - the number of comments to show by default. Default: 10. Minimum: 1
		mobile - whether to show the mobile-optimized version. Default: auto-detect.
	*/
	public function getComments($href,$args=array()){
		$p['data-href']=$href;
		$p['data-width']=isset($args['width'])?(int)$args['width']:400;
		$p['data-colorscheme']=isset($args['color'])?$args['color']:'light';
		$p['data-num-posts']=isset($args['posts'])?(int)$args['posts']:10;
		$p['data-mobile']=isset($args['mobile'])?$args['mobile']:'auto-detect';
		$ps=array();
		foreach($p as $k=>$v)
			$ps[]=$k.'="'.$v.'"';
		return $this->addScriptOnce('facebook-js-sdk',$this->sdk).
		'<div class="fb-comments" '.implode(' ',$ps).'></div>'.$this->jsSDK;
	}
	
	
	
	
	/*
	SUBSCRIBE
	args
		href - profile URL of the user to subscribe to. This must be a facebook.com profile URL.
	layout - there are three options.
	standard - displays social text to the right of the button and friends' profile photos below. Minimum width: 225 pixels. Default width: 450 pixels. Height: 35 pixels (without photos) or 80 pixels (with photos).
	button_count - displays the total number of subscribers to the right of the button. Minimum width: 90 pixels. Default width: 90 pixels. Height: 20 pixels.
	box_count - displays the total number of subscribers above the button. Minimum width: 55 pixels. Default width: 55 pixels. Height: 65 pixels.
	show_faces - specifies whether to display profile photos below the button (standard layout only)
	colorscheme - the color scheme for the plugin. Options: 'light' (default) and 'dark'
	font - the font to display in the plugin. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	width - the width of the plugin.
	*/
	public function getSubscribeButton($profileURL,$args=array()){
		$p['data-href']=$profileURL;
		$p['data-layout']=isset($args['layout'])?$args['layout']:'button_count';
		$p['data-show-faces']=isset($args['faces'])?$args['faces']:'true';
		$p['data-colorscheme']=isset($args['color'])?$args['color']:'light';
		$p['data-font']=isset($args['font'])?$args['font']:'arial';
		$p['data-width']=isset($args['width'])?(int)$args['width']:90;
		$ps=array();
		foreach($p as $k=>$v)
			$ps[]=$k.'="'.$v.'"';
		return $this->addScriptOnce('facebook-js-sdk',$this->sdk).
		'<div class="fb-subscribe" '.implode(' ',$ps).'></div>'.$this->jsSDK;
	}
	
	
	
	
	/*
	LIKE BOX
	args
		href - the URL of the Facebook Page for this Like Box
		width - the width of the plugin in pixels. Default width: 300px.
		height - the height of the plugin in pixels. The default height varies based on number of faces to display, and whether the stream is displayed. With the stream displayed, and 10 faces the default height is 556px. With no faces, and no stream the default height is 63px.
		colorscheme - the color scheme for the plugin. Options: 'light', 'dark'
		show_faces - specifies whether or not to display profile photos in the plugin. Default value: true.
		stream - specifies whether to display a stream of the latest posts from the Page's wall
		header - specifies whether to display the Facebook header at the top of the plugin.
		border_color - the border color of the plugin.
		force_wall - for Places, specifies whether the stream contains posts from the Place's wall or just checkins from friends. Default value: false.
	*/
	public function getLIkeBox($pageURL,$args=array()){
		$p['data-href']=$pageURL;
		$p['data-width']=isset($args['width'])?(int)$args['width']:300;
		$p['data-height']=isset($args['height'])?(int)$args['height']:556;
		$p['data-colorscheme']=isset($args['color'])?$args['color']:'light';
		$p['data-show-faces']=isset($args['faces'])?$args['faces']:'true';
		$p['data-stream']=isset($args['stream'])?$args['stream']:'true';
		$p['data-header']=isset($paras['header'])?$args['header']:'true';
		$p['data-border-color']=isset($args['border'])?$args['border']:'#aaaaaa';
		$ps=array();
		foreach($p as $k=>$v)
			$ps[]=$k.'="'.$v.'"';
		return $this->addScriptOnce('facebook-js-sdk',$this->sdk).
		'<div class="fb-like-box" '.implode(' ',$ps).'></div>'.$this->jsSDK;
	}
	
	
	private function addScriptOnce($scriptId,$src){
		return '<script>(function(d,s,id){var js,fjs=d.body.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement("script");js.id=id;js.type="text/javascript";js.src="'.$src.'";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","'.$scriptId.'"));</script>';
	}
}
?>