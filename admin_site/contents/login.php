<?php
$form = Won::get('User')->login_form();
$error = Won::get('User')->error? Won::get('User')->error . '<br/>' : '';
?>
<p style="color:#f00"><?=$error?></p>
<?=$form?>

