# Apache config example
```
# default.conf

Listen 127.0.0.1:8888
<VirtualHost 127.0.0.1:8888>
	ServerAdmin webmaster@localhost
	DocumentRoot /home/bitrix/website/www/web

	<Directory />
		Options FollowSymLinks
		AllowOverride None
	</Directory>

	<DirectoryMatch .*\.svn/.*>
		 Deny From All
	</DirectoryMatch>

	<DirectoryMatch .*\.git/.*>
		 Deny From All
	</DirectoryMatch>

	<DirectoryMatch .*\.hg/.*>
		 Deny From All
	</DirectoryMatch>

	<Directory /home/bitrix/website/www/web>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		DirectoryIndex index.php index.html index.htm
		Order allow,deny
		allow from all
		php_admin_value session.save_path /tmp/php_sessions/www
		php_admin_value upload_tmp_dir /tmp/php_upload/www
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/bitrix/cache>
		AllowOverride none
		Order allow,deny
		Deny from all
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/bitrix/managed_cache>
		AllowOverride none
		Order allow,deny
		Deny from all
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/bitrix/local_cache>
		AllowOverride none
		Order allow,deny
		Deny from all
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/bitrix/stack_cache>
		AllowOverride none
		Order allow,deny
		Deny from all
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/upload>
		AllowOverride none
		AddType text/plain php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht
		php_value engine off
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/upload/support/not_image>
		AllowOverride none
		Order allow,deny
		Deny from all
	</Directory>

	<Directory /home/bitrix/website/www/btxapp/bitrix/images>
		AllowOverride none
		AddType text/plain php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht
		php_value engine off
	</Directory>

	<Directory /home/bitrix/website/www/btxapp>
		AllowOverride none
		AddType text/plain php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht
		php_value engine off
	</Directory>

	ErrorLog logs/error_log
	# Possible values include: debug, info, notice, warn, error, crit, alert, emerg.
	LogLevel warn

	CustomLog logs/access_log combined

	<IfModule mod_rewrite.c>
		#Nginx should have "proxy_set_header HTTPS YES;" in location
		RewriteEngine On
		RewriteCond %{HTTP:HTTPS} =YES
		RewriteRule .* - [E=HTTPS:on,L]
	</IfModule>
</VirtualHost>
```