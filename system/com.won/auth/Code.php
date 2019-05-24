<?php
namespace com\won\auth;
class Code {
	
	public function encrypt($string) {
		
		$code = md5($string); // 32 length
		$f = substr($code, 0, 16);
		$e = substr($code, 16, 16);
		return $f . base64_encode(base64_encode($string)) . $e;
	}
	
	public function decrypt($encryptedCode) {
		
		$f = substr($encryptedCode, 0, 16);
		$e = substr($encryptedCode, strlen($encryptedCode)-16, 16);
		$string = substr($encryptedCode, 0, strlen($encryptedCode)-16);
		$string = substr($string, 16, strlen($string)-16);
		return base64_decode(base64_decode($string));
	}
}
?>