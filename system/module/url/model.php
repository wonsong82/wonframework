<?php
class UrlModel extends Model {
		
	protected function init() {
		
		$this->setField('url.uri', 'uri', 'VARCHAR(255)', '#^[a-zA-Z0-9/-_$]*$#', 'UNIQUE');
		$this->setField('url.template', 'template', 'VARCHAR(255)', '#^[a-zA-Z0-9.-_]+\.php$#', 'INDEX');
		
		$this->setError('INVALID_URI', 'Invalid URI format.');
		$this->setError('EXISTING_URI', 'Uri {$uri} already exist.');
		$this->setError('INVALID_TEMPLATE', 'Invalid Template format');							
	}	
	
	
	//Check wheter the $uri already exists
	public function exist($uri) {
		
		$uri = $this->db->escape($uri);
		$urlTable = $this->table['url']['name'];
		$uriField = $this->table['url']['fields']['uri']['name'];
		$this->db->query("
			SELECT `id` FROM `{$urlTable}`
			WHERE `{$uriField}`='{$uri}'
		");
		return $this->db->numRows() ? true : false;
	}
	
	
	//Add link to the table
	public function add($uri, $template) {		
		
		$uri = $this->db->escape($uri);
		$template = $this->db->escape($template);
		$urlTable = $this->table['url']['name'];
		$uriField = $this->table['url']['fields']['uri']['name'];
		$templateField = $this->table['url']['fields']['template']['name'];
		$this->db->query("
			INSERT INTO `{$urlTable}`
			SET `{$uriField}`='{$uri}',
				`{$templateField}`='{$template}'
		");				
	}
	
	//Remove link from the table
	public function remove($id) {
		
		$id = (int)$id;
		$urlTable = $this->table['url']['name'];	
		$this->db->query("
			DELETE FROM `{$urlTable}`
			WHERE `id`={$id}
		");
	}
	
		
	public function getTemplate($uri) {
		
		$uri = $this->db->escape($uri);
		$urlTable = $this->table['url']['name'];
		$uriField = $this->table['url']['fields']['uri']['name'];
		$templateField = $this->table['url']['fields']['template']['name'];
		$allFields = $this->getFields('url');
		
		//Static page Found
		$templates = $this->db->query("
			SELECT {$allFields} FROM `{$urlTable}`
			WHERE `{$uriField}` = '{$uri}'
		");		
		if ($this->db->numRows()) {
			return $templates[0];
		}		
		
		//Dynamic page
		$templates = $this->db->query("
			SELECT {$allFields} FROM `{$urlTable}`
			WHERE `{$uriField}` LIKE '%\$%'
		");
		if ($this->db->numRows()) {
			foreach ($templates as $template) {
				$bits = explode('$', $template['uri']);
				$staticUri = trim($bits[0], '/');
				
				if (preg_match('/^'.$staticUri.'/i', $uri)) {
					return $template;
				}
			}
		}
		
		//404
		$templates = $this->db->query("
			SELECT {$allFields} FROM `{$urlTable}`
			WHERE `{$uriField}` = '404error'
		");
		if (isset($templates[0])) {
			return $templates[0];
		}		
	}	
	
	
}
?>