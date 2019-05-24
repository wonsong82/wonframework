<?php
header("Content-type: text/css", true);
preg_match('/https?/i',$_SERVER['SERVER_PROTOCOL'],$protocol);
$url=strtolower($protocol[0]).'://'.$_SERVER['HTTP_HOST'].str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_NAME']);
?>
@charset "utf-8";
/* CSS Document */
@font-face {
	font-family: helvetica bold;
    src: url(<?=$url?>HelveticaRoundedLTStd-Bd.otf);
    font-weight:400;
}
