<?php
final class MagicQuotesFix {
	
	public function __construct() {
		if (ini_get('magic_quotes_gpc')) {
			$_GET = $this->clean($_GET);
			$_POST = $this->clean($_POST);
			$_REQUEST = $this->clean($_REQUEST);
			$_COOKIE = $this->clean($_COOKIE);
		}
	}
	
	private function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key=>$value) {
				$data[$this->clean($key)] = $this->clean($value);
			}
		}
		else {
			$data = stripslashes($data);
		}
		return $data;
	}
}
?>