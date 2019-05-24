<?php
Won::get('User')->logout();
header('location:'.Won::get('Config')->admin_url);
?>