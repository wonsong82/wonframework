<?php
// Name : PHPass
// Desc : Strong Passphrase Generator, replacing MD5, instead of MD5's static value, phpass changes the value all the time
namespace com\won\auth;

class PHPass{
	
	private $passwordHashObj;
	
	public function __construct($iterationCountLog2=8, $portableHashes=false){
		require_once dirname(__FILE__).'/PasswordHash/PasswordHash.php';
		$this->passwordHashObj = new \PasswordHash($iterationCountLog2, $portableHashes);
	}
	
	public function getHash($rawstring){
		return $this->passwordHashObj->HashPassword($rawstring);
	}
	
	public function checkHash($rawstring, $hashstring){
		return $this->passwordHashObj->CheckPassword($rawstring, $hashstring);
	}
}
?>