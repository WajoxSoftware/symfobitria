# Nginx config example
```
# s1.conf
# Default website
server {

	listen 80 default_server;
	server_name _;
	server_name_in_redirect off;

	proxy_set_header	X-Real-IP        $remote_addr;
	proxy_set_header	X-Forwarded-For  $proxy_add_x_forwarded_for;
	proxy_set_header	Host $host:80;

	set $proxyserver	"http://127.0.0.1:8888/";
	set $docroot		"/home/bitrix/website/www/btxapp";

	index index.php;
	root /home/bitrix/website/www/btxapp;

	# Redirect to ssl if need
	if (-f /home/bitrix/website/www/.htsecure) { rewrite ^(.*)$ https://$host$1 permanent; }

	# Include parameters common to all websites
	include bx/conf/bitrix.conf;

	# Include server monitoring locations
	include bx/server_monitor.conf;
}

```
```
# ssl.s1.conf
# Default SSL certificate enabled website
	server {
		listen	443 default_server ssl;
		server_name _;

		# Enable SSL connection
		include	bx/conf/ssl.conf;
		server_name_in_redirect	off;

		proxy_set_header	X-Real-IP	$remote_addr;
		proxy_set_header	X-Forwarded-For	$proxy_add_x_forwarded_for;
		proxy_set_header	Host		$host:443;
		proxy_set_header	HTTPS 		YES;

		set $proxyserver	"http://127.0.0.1:8888";
		set $docroot		"/home/bitrix/website/www/btxapp";

		index index.php;
		root /home/bitrix/website/www/btxapp;

		# Include parameters common to all websites
		include bx/conf/bitrix.conf;

		# Include server monitoring API's
		include bx/server_monitor.conf;

	}

```