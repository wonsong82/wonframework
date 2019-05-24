<?php
$out['status']='no'; // Whether there was an error or success
$out['data']=''; // Any output of data
$out['error']=''; // If error, display error message

// If this is outside of Admin auth, set error
$this->user->auth();
if(!$this->user->isAdmin){
	$out['status']='no';
	$out['error']=$this->user->getText('UNAUTHORIZED_USER');
	echo json_encode($out);
	exit();
}



$args=isset($this->req->post['params'])? $this->req->post['params']:array();

$class=$this->url->args['c'];
$method=$this->url->args['m'];
if(!@method_exists($this->$class, $method)){
	$out['status']='no';
	$out['error']=$this->url->args['m'];
	echo json_encode($out);
	exit();
}
$call = @call_user_func_array(array($this->$class, $method), $args);

if(false===$call){
	$out['status']='no';
	$out['error']=$this->$class->lastError();
	echo json_encode($out);
	exit();
}
$out['status']='ok';
$out['data']=$call;
$out['error']='';
echo json_encode($out);
exit();
?>