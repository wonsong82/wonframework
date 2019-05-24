<?php
Won::set(new Contact());
Won::get('Contact')->add();
header('location:'.Won::get('Config')->admin_url.'/'.Won::get('Config')->this_module);
?>