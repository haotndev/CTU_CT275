## Triển khai trên Apache HTTP

```
# C:/xampp/apache/conf/extra/httpd-vhosts.conf

<VirtualHost *:80>	
	DocumentRoot "C:/xampp/htdocs" 
	ServerName localhost
</VirtualHost>

<VirtualHost *:80>	
	DocumentRoot "D:/Projects/mysites/lab4/public"
	ServerName ct275-lab4.localhost
	
	# Set access permission 
	<Directory "D:/Projects/mysites/lab4/public">
		Options -Indexes -FollowSymLinks -Includes -ExecCGI
		AllowOverride All
		Require all granted

		RewriteEngine On
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule . index.php [L]
	</Directory>
</VirtualHost>
```

