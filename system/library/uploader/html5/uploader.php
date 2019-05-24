<?php

$a=((bool)$_POST['first'])?
	fopen($_POST['name'],'w'):
	fopen($_POST['name'],'a');

$data=preg_replace('#^.+,#','',$_POST['data']);
fwrite($a,base64_decode($data));
fclose($a);
var_dump($_POST);
?>