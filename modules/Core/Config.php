<?php
class Config
{
	/**
	 * @name $site_url
	 * @desc URL of the site
	 * @type string
	 */
	public $site_url;
	
	/**
	 * @name $site_dir
	 * @desc Directory of the site
	 * @type string
	 */
	public $site_dir;
	
	/**
	 * @name $content_url
	 * @desc URL of contents
	 * @type string
	 */
	public $content_url;
	
	/**
	 * @name $content_dir
	 * @desc Directory of contents
	 * @type string
	 */
	public $content_dir;
	
	/**
	 * @name $admin_url
	 * @desc URL of admin
	 * @type string
	 */
	public $admin_url;
	
	/**
	 * @name $admin_dir
	 * @desc Directory of admin
	 * @type string
	 */
	public $admin_dir;
	
	/**
	 * @name $admin_content_url
	 * @desc URL of admin contents
	 * @type string
	 */
	public $admin_content_url;
	
	/**
	 * @name $admin_content_dir
	 * @desc Directory of admin contents
	 * @type string
	 */
	public $admin_content_dir;
	
	/**
	 * @name $module_url
	 * @desc URL of module root
	 * @type string
	 */
	public $module_url;
	
	/**
	 * @name $module_dir
	 * @desc Directory of module root
	 * @type string
	 */
	public $module_dir;
	
	/**
	 * @name $include_dir
	 * @desc Directory of includes in contents
	 * @type string
	 */
	public $include_dir;	
	
	/**
	 * @name $loaded
	 * @desc Whether config is loaded || not
	 * @type bool
	 */
	public $loaded;
}
?>