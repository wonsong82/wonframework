<?php
// Login Authentication
Won::set(new User());
Won::get('User')->authenticate();

// if user is not logged
if (!Won::get('User')->logged)
{
	require Won::get('Config')->admin_content_dir . '/login.php';
	exit();	
}

// if user is not a member of administrator
elseif (!Won::get('User')->member_of('Administrator'))
{
	require Won::get('Config')->admin_content_dir . '/login.php';
	exit();
}


// Define Admin Configs
Won::get('Config')->this_module 	= Won::get('Permalink')->params['module'];
Won::get('Config')->this_module_dir = Won::get('Config')->module_dir .'/' . Won::get('Config')->this_module;
Won::get('Config')->this_module_url	= Won::get('Config')->module_url .'/' . Won::get('Config')->this_module;


// Get Module Lists that has Admin Panel
Won::set(new FileSystem());
$modules = array();
$i = 100;
foreach (Won::get('FileSystem')->read_directory(Won::get('Config')->module_dir) as $module)
{
	if (		
		file_exists(Won::get('Config')->module_dir.'/'.$module.'/'.$module.'.php') &&
		is_dir(Won::get('Config')->module_dir.'/'.$module.'/admin')
	) 
	{
		$order = preg_match('#ModuleNavigationSortOrder=([0-9]+)#',file_get_contents(Won::get('Config')->module_dir.'/'.$module.'/'.$module.'.php',null,null,null,50), $match)? $match[1] : $i;
		
		$modules[$order] = $module;
		$i++;
	}
}
ksort($modules);

// Parse Submenus
$admin_pages = array();
if (is_dir(Won::get('Config')->this_module_dir . '/admin'))
{
	$admin_files = Won::get('FileSystem')->read_directory(Won::get('Config')->this_module_dir . '/admin');
	sort($admin_files);
	foreach ($admin_files as $admin_file)
	{
		$admin_file = basename($admin_file);
		if (preg_match('#^[0-9]_.+?\.php$#i', $admin_file))
			$admin_pages[] = preg_replace('#^[0-9]_(.+?)\.php$#i', '$1', $admin_file);
	}
}


// Load Contents

	// admin landing page
if (Won::get('Permalink')->uri == 'admin')
{
	require Won::get('Config')->admin_content_dir . '/header.php';
	require Won::get('Config')->admin_content_dir . '/footer.php';
}

	// admin logout page
elseif (Won::get('Permalink')->uri == 'admin/logout')
{
	require Won::get('Config')->admin_content_dir . '/logout.php';
}

	// module is requested, page is missing, then firstpage
elseif (count($admin_pages) && !Won::get('Permalink')->params['page'])
{
	Won::get('Config')->this_module_page = $admin_pages[0];
	Won::get('Config')->this_module_page_file = Won::get('Config')->this_module_dir . '/admin/' . '0_' . Won::get('Config')->this_module_page . '.php';
	
	require Won::get('Config')->admin_content_dir . '/header.php';
	require Won::get('Config')->admin_content_dir . '/main.php';
	require Won::get('Config')->admin_content_dir . '/footer.php';
}


	// module and its page is requested
elseif (count($admin_pages) && false!==($key=array_search(Won::get('Permalink')->params['page'], $admin_pages)))
{
	Won::get('Config')->this_module_page = Won::get('Permalink')->params['page'];
	Won::get('Config')->this_module_page_file = Won::get('Config')->this_module_dir . '/admin/' . $key . '_' . Won::get('Config')->this_module_page . '.php';
	
	require Won::get('Config')->admin_content_dir . '/header.php';
	require Won::get('Config')->admin_content_dir . '/main.php';
	require Won::get('Config')->admin_content_dir . '/footer.php';
}

	// else display 404 error page
else
{
	require Won::get('Config')->admin_content_dir . '/header.php';
	require Won::get('Config')->admin_content_dir . '/404error.php';
	require Won::get('Config')->admin_content_dir . '/footer.php';
}
?>


