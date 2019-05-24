<?php
var_dump($_FILES);
move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
?>