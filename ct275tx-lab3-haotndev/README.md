## Triển khai trên Apache HTTP

```
# C:/xampp/apache/conf/extra/httpd-vhosts.conf

<VirtualHost *:80> 
    DocumentRoot "C:/xampp/htdocs" 
    ServerName localhost
</VirtualHost>

<VirtualHost *:80> 
    DocumentRoot "D:/Projects/mysites/lab3/public"
    ServerName ct275-lab3.localhost
    # Set access permission 
    <Directory "D:/Projects/mysites/lab3/public">
        Options -Indexes -FollowSymLinks -Includes -ExecCGI
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
