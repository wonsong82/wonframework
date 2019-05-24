<?php
$htaccess_tpl = <<<HTA
<IfModule mod_rewrite.c>
SetEnv HTTP_MOD_REWRITE On
RewriteEngine On
RewriteBase {$base}
RewriteRule ^index\.php\$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$base}index.php [L]
</IfModule>
HTA;
?>