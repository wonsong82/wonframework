<?php
require_once '../../config.php';
SimplePassword::logout('admin');
header('location:'.ADMIN_URL);
?>