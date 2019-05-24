Options +FollowSymlinks
RewriteEngine On
RewriteBase {$base}

# This is for domain/index.php
RewriteRule ^index\.php$ - [L]

# This is required for passing requests properly for DBHandler
RewriteRule ^{$dbhandler_uri_trim}$ {$base}_db_handler/index.php [L]
RewriteRule ^{$dbhandler_uri_trim}/$ {$base}_db_handler/index.php [L]

# This redirects everything to index.php unless the file or directory exists
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$base}index.php [L]