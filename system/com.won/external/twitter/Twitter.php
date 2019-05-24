<?php
class Twitter{
	
	//This is a prerequisite, SDK must be included first
	public $sdk = '//platform.twitter.com/widgets.js';
	
	
	/* Share Button 
	shares the contents of the given url.
	url:any url, default = current url
	title:title within the tweet, default = current page title
	via: @username
	recommend: @username
	hashtag: tag
	large: true or false, larger icon, default false
	lang: lang_code, default = en
	count: show or hide count true or false
	text: text in the icon default Tweet
	*/
	public function shareButton($args=array()){
		$p='';
		$p.=isset($args['url'])?' data-url="'.$args['url'].'"':'';
		$p.=isset($args['title'])?' data-text="'.$args['title'].'"':'';
		$p.=isset($args['via'])?' data-via="'.$args['via'].'"':'';
		$p.=isset($args['lang'])?' data-lang="'.$args['lang'].'"':'';
		$p.=isset($args['recommend'])?' data-related="'.$args['recommend'].'"':'';
		$p.=isset($args['large'])&&$args['large']==true?' data-size="large"':'';
		$p.=isset($args['count'])&&$args['count']==false?' data-count="none"':'';
		$p.=isset($args['hashtag'])?' data-hashtags="'.$args['hashtag'].'"':'';
		$t=isset($args['text'])?$args['text']:'Tweet';		
		return $this->addScriptOnce('twitter-widgets-js-sdk',$this->sdk).
		'<a href="https://twitter.com/share" class="twitter-share-button"'.$p.'>'.$t.'</a>';	
	}
	
	
	public function FollowButton($username,$args=array()){
		$p='';
		$p.=isset($args['large'])&&$args['large']==true?' data-size="large"':'';
		$p.=isset($args['lang'])?' data-lang="'.$args['lang'].'"':'';
		$t=isset($args['text'])?$args['text']:'Follow';
		$t.=isset($args['showname'])&&$args['showname']==true?' @'.$username:'';
		return $this->addScriptOnce('twitter-widget-js-sdk',$this->sdk).
		'<a href="https://twitter.com/twitter" class="twitter-follow-button"'.$p.'>'.$t.'</a>';
	}
	
	public function HashtagButton($hashtag,$args=array()){
		$title=isset($args['title'])?'&text='.urlencode($title):'';
		$p='';
		$p.=isset($args['url'])?' data-url="'.$args['url'].'"':'';
		$p.=isset($args['recommend'])?' data-related="'.$args['recommend'].'"':'';
		$p.=isset($args['lang'])?' data-lang="'.$args['lang'].'"':'';
		$p.=isset($args['large'])&&$args['large']==true?' data-size="large"':'';
		$t=isset($args['text'])?$args['text']:'Tweet';
		$t.=' #'.$hashtag;
		return $this->addScriptOnce('twitter-widgets-js-sdk',$this->sdk).
		'<a href="https://twitter.com/intent/tweet?button_hashtag='.$hashtag.$title.'" class="twitter-hashtag-button"'.$p.'>'.$t.'</a>';
	}
	
	public function MentionButton($username,$args=array()){
		$title=isset($args['title'])?'&text='.urlencode($title):'';
		$p='';
		$p.=isset($args['recommend'])?' data-related="'.$args['recommend'].'"':'';
		$p.=isset($args['large'])&&$args['large']==true?' data-size="large"':'';
		$p.=isset($args['lang'])?' data-lang="'.$args['lang'].'"':'';
		$t=isset($args['text'])?$args['text']:'Tweet to';
		$t.=' @'.$username;
		return $this->addScriptOnce('twitter-widgets-js-sdk',$this->sdk).
		'<a href="https://twitter.com/intent/tweet?screen_name='.$username.$title.'" class="twitter-mention-button"'.$p.'>'.$t.'</a>';
	}
	
	
	private function addScriptOnce($scriptId,$src){
		return '<script>(function(d,s,id){var js,fjs=d.body.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement("script");js.id=id;js.type="text/javascript";js.src="'.$src.'";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","'.$scriptId.'"));</script>';
	}
}
?>