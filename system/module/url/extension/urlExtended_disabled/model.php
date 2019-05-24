<?php
class UrlExtendedModel extends ModelExtension {
	
	protected function init() {
		
		$this->setField('url.uri', 'uri_cn', 'VARCHAR(255)', '#^.*$#', 'UNIQUE');
		$this->setField('url.title', 'title', 'VARCHAR(255)', '#^.*$#');
		
		$this->setError('uri', '한국말에러');	
			
	}
	
	public function updateTitle($title) {
		
		var_dump($title);
	}
}
?>