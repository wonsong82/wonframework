<?php
include 'system/library/db/sqlite/Sqlite.php';
$db = new Sqlite();
$db->connect('KakaoTalk.db');

$friends = $db->query("SELECT * FROM friends");

echo '<pre>';
print_r($friends);
echo '</pre>';
?>