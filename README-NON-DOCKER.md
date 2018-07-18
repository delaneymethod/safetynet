# SafetyNet

[![Build Status](http://165.227.234.36/buildStatus/icon?job=safetynet)](http://165.227.234.36/job/safetynet)

SafetyNet is an internal platform for Survitec Group Sales staff.

## Technologies

* PHP FPM
* MariaDB
* Nginx
* Redis
* Node
* Laravel
* Ubuntu
* Jenkins

## Prerequisites

This document focuses on running the SafetyNet web app in a non-Docker environment and that you have Git and Laravel experience/knowledge.

## DEVELOPMENT

This section assumes you already have a local running web server stack setup (e.g MAMP if you are running on a Mac).

### Installation

Clone the `master` branch from Git repo to your local machine. 

Navigation to where you have cloned the project files.

Rename `*.env.example` to `.env` and replace the following variables with suitable values.

You may need to set permissions on the directories that Laravel needs to write to. 

The following will let Laravel write the storage and bootstrap directories:

`chmod -R o+rw bootstrap storage`

Install Composer modules:

`composer update`

Install NPM modules:

`npm install`

Create a new Laravel Application Key:

`php artisan key:generate`

### Data Migration

Take down Laravel:

`php artisan down --message="Upgrading Database" --retry=60`

Run Laravel migration and seed:

`php artisan migrate:fresh --seed`

Run Laravel data import:

`php artisan db:import`

We can also import data from other locations by passing in a `--path` option:

`php artisan db:import --path=/path/to/data.sql`

Bring Laravel back up again:

`php artisan up`

### Running

Build and watch assets (JS, Sass, Fonts, Images etc):

`npm run watch`

Start your local web server stack, if not already running.

You should be able to visit the SafetyNet web app at the hostname or local IP address in your browser.

### Miscellanous

#### Sequel Pro for OSX

You can connect to the MariaDB database using the following details or whatever details you set up in `.env`:

* Host: `127.0.0.1`
* Username: `safetynet`
* Password: `safetynet`
* Database: `safetynet`
* Port: `3306`

#### Laravel worker queues

Run Laravel worker queues:

`php artisan queue:work --queue=default --daemon`

For more information on running daemon worker queues, see [https://laravel.com/docs/5.0/queues#daemon-queue-worker](https://laravel.com/docs/5.0/queues#daemon-queue-worker)

Restart Laravel worker queues:

`php artisan queue:restart`

Stopping Laravel worker queues:

TL;DR - There is no safe way! We could use the Artisan command `php artisan down` which will stop all queues.

Regardless of how we stop daemons, I suggest you have a read of [https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent](https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent)

## PRODUCTION

This section assumes you already have a Digital Ocean account with a new droplet running Ubuntu 16.04 with a LEMP stack installed and read to go.

Please note other providers can be used such as AWS or Azure. You would obviously have to swap out specific Digital Ocean values.

### LEMP Stack Configuration.

SSH into the droplet.

Changed root password.

View default MySQL password by running `cat /root/.digitalocean_password`

Ran `mysql_secure_installation`

Ran `sudo ufw status` to make sure all ports required are open. Should be since we created a Firewall during Droplet configuration in DO's control panel.

Ran `sudo apt-get update`

Ran `sudo apt-get upgrade`

Ran `sudo add-apt-repository -y ppa:ondrej/php`

Ran `apt-get update`

Ran `sudo apt-get install -y build-essential libssl-dev sendmail`

Ran `sudo apt-get install -y jpegoptim libjpeg-progs optipng pngcrush gifsicle curl zip unzip git software-properties-common supervisor sqlite3`

Ran `sudo apt-get install -y php7.1-fpm php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd php7.1-mysql php7.1-pgsql php7.1-sqlite php7.1-imap php7.1-memcached php7.1-mbstring php7.1-imagick php7.1-xml php7.1-curl php7.1-sqlite3 php7.1-xdebug`

Ran `sudo php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && sudo mkdir /run/php`

Ran `sudo npm install -g gulp`

Ran `sudo apt-get remove -y --purge software-properties-common`

Ran `sudo apt-get acl`

Ran `sudo apt-get autoremove`

Ran `sudo apt-get autoclean`
 
Ran `sudo rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*`

Read over [https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04](https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04) if you are having RAM issues.

Read over [https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-16-04](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-16-04) to install Node and NPM.

#### MariaDB Installation and Configuration

Ran `sudo apt-get update`

Ran `sudo apt-get install mariadb-server`

Ran `sudo pico /etc/mysql/mariadb.conf.d/50-server.cnf`

Updated

`#bind-address = 127.0.0.1`

Added 

```
key_buffer_size = 16M
max_allowed_packet = 16M
thread_stack = 192K
thread_cache_size = 8
myisam-recover = BACKUP
max_connections = 100
table_cache = 64
thread_concurrency = 10

sql_mode=STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION

innodb_buffer_pool_size = 32M
innodb_additional_mem_pool_size = 2M
innodb_log_file_size = 10M
innodb_log_buffer_size = 32M
innodb_flush_log_at_trx_commit = 1
innodb_lock_wait_timeout = 50
innodb_strict_mode = off
innodb_file_per_table = 1
innodb_file_format = barracuda
```

Ran `sudo service mysql restart`

Ran `mysql_secure_installation`

Ran the user and database config steps below to add back in your user and permissions.

#### MySQL Installation and Configuration

Ran `sudo apt-get update`

Ran `sudo apt-get install mysql-server`

Ran `sudo pico /etc/mysql/mysql.conf.d/mysqld.cnf`

Added 

```
sql_mode=STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION

innodb_buffer_pool_size = 32M
innodb_additional_mem_pool_size = 2M
innodb_log_file_size = 10M
innodb_log_buffer_size = 32M
innodb_flush_log_at_trx_commit = 1
innodb_lock_wait_timeout = 50
innodb_strict_mode = off
innodb_file_per_table = 1
innodb_file_format = barracuda
```

Ran `sudo service mysql restart`

Ran `mysql_secure_installation`

Ran the user and database config steps below to add back in your user and permissions.

#### User and Database Configuration

Ran `mysql -u root -p`

Entered the default MySQL password.

Ran `SHOW DATABASES;`

Ran `CREATE DATABASE safetynet;`

Ran `SHOW DATABASES;`

Ran `SELECT host, user, password FROM mysql.user;`

Ran `CREATE USER 'safetynet'@'localhost' IDENTIFIED BY 'safetynet';`

Ran `CREATE USER 'safetynet'@'%' IDENTIFIED BY 'safetynet';`

Ran `GRANT ALL PRIVILEGES ON * . * TO 'safetynet'@'localhost' WITH GRANT OPTION;`

Ran `GRANT ALL PRIVILEGES ON * . * TO 'safetynet'@'%' WITH GRANT OPTION;`
    
Ran `FLUSH PRIVILEGES;`

Ran `quit;`

Ran `sudo service mysql restart`

#### NGINX Configuration

Ran `sudo pico /etc/nginx/nginx.conf`

```
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
	worker_connections 1024;
	multi_accept on;
	use epoll;
}

worker_rlimit_nofile 40000;

http {
	open_file_cache max=1000 inactive=20s;
	open_file_cache_valid 30s;
	open_file_cache_min_uses 5;
	open_file_cache_errors off;

	##
	# Basic Settings
	##
	
	sendfile on;
	tcp_nopush on;
	tcp_nodelay on;
	keepalive_timeout 65;
	types_hash_max_size 2048;
	# server_tokens off;
	
	# server_names_hash_bucket_size 64;
	# server_name_in_redirect off;
	
	include /etc/nginx/mime.types;
	default_type application/octet-stream;
	
	##
	# SSL Settings
	##
	
	ssl_protocols TLSv1 TLSv1.1 TLSv1.2; # Dropping SSLv3, ref: POODLE
	ssl_prefer_server_ciphers on;
	
	##
	# Logging Settings
	##

	log_format main '$remote_addr - $remote_user [$time_local] "$request" '
			'$status $body_bytes_sent "$http_referer" '
			'"$http_user_agent" "$http_x_forwarded_for"';
	
	access_log /var/log/nginx/access.log main;
	error_log /var/log/nginx/error.log warn;
	
	##
	# Gzip Settings
	##

	gzip on;
	gzip_disable "msie6";

	gzip_vary on;
	gzip_min_length 10240;
	gzip_proxied expired no-cache no-store private auth;
	gzip_comp_level 6;
	gzip_buffers 16 8k;
	gzip_http_version 1.1;
	gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;
	
	##
	# Virtual Host Configs
	##

	include /etc/nginx/conf.d/*.conf;
	include /etc/nginx/sites-enabled/*;
}
```

Ran `sudo pico /etc/nginx/sites-available/digitalocean` to check and add rewrite rules

```
server {
	client_max_body_size 100m;
	client_header_buffer_size 1k;
	client_body_buffer_size 256k;
	large_client_header_buffers 4 16k;
	
	listen 80 default_server;
	listen [::]:80 default_server ipv6only=on;

	root /var/www/html/public;
	index index.php index.html index.htm;

	# Make site accessible from http://localhost/ or Droplet IP Addresss or Domain name here
	server_name _;
	
	charset utf-8;
	
	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ /index.php?$query_string;
		
		# Remove index.php
		rewrite ^/index\.php(.*) $1 permanent;
	
		# Uncomment to enable naxsi on this location
		# include /etc/nginx/naxsi.rules
	}
	
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
	
		fastcgi_buffer_size 128k;
		fastcgi_buffers 256 16k;
		fastcgi_busy_buffers_size 256k;
		fastcgi_temp_file_write_size 256k;
		
		fastcgi_pass unix:/run/php/php7.1-fpm.sock;
	}
	
	location ~* (?:^|/)\. {
		access_log off;
		log_not_found off;
		deny all;
	}
	
	location = /favicon.ico {
		access_log off;
		log_not_found off;
	}
	
	location = /robots.txt {
		access_log off;
		log_not_found off;
	}
	
	location ~* (?:\.(?:bak|config|sql|fla|psd|ini|log|sh|inc|swp|dist)|~)$ {
		access_log off;
		log_not_found off;
		deny all;
	}
	
	location ~* \.(?:manifest|appcache|html?|xml|json)$ {
		access_log off;
		log_not_found off;
		try_files $uri /index.php?$query_string;
		expires -1;
	}
	
	location ~* \.(?:rss|atom)$ {
		access_log off;
		log_not_found off;
		try_files $uri /index.php?$query_string;
		expires 1h;
		add_header Cache-Control "public";
	}
	
	location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
		access_log off;
		log_not_found off;
		try_files $uri /index.php?$query_string;
		expires 360d;
		add_header Cache-Control "public";
	}
	
	location ~* \.(?:css|js)$ {
		access_log off;
		log_not_found off;
		try_files $uri /index.php?$query_string;
		expires 1y;
		add_header Cache-Control "public";
	}
	
	location ~* \.(?:ttf|ttc|otf|eot|woff)$ {
		access_log off;
		log_not_found off;
		try_files $uri /index.php?$query_string;
		expires 1M;
		add_header Cache-Control "public";
	}
	
	# deny access to .htaccess files, if Apache's document root concurs with nginx's one
	location ~ /\.ht {
		access_log off;
		log_not_found off;
		deny all;
	}
}
```

Ran `sudo service nginx reload`

#### PHP-FPM Configuration

Ran `sudo pico /etc/php/7.1/fpm/php-fpm.conf`

Updated

```
emergency_restart_threshold 10
emergency_restart_interval 1m
process_control_timeout 10s
process.max = 256
rlimit_files = 4096
rlimit_core = 0
```

Ran `sudo pico /etc/php/7.1/fpm/pool.d/www.conf`

Updated

```
pm = ondemand
pm.max_children = 75
pm.start_servers = 15
pm.min_spare_servers = 15
pm.max_spare_servers = 25
pm.process_idle_timeout = 10s
pm.max_requests = 500
rlimit_files = 4096
rlimit_core = 0
php_admin_value[memory_limit] = 256M
php_value[memory_limit] = 256M
```
Ran `sudo service php7.1-fpm restart`

#### Rebooting Droplet from SSH Terminal

Run `sudo reboot`

#### Restarting Services, Statuses and Versions

Ran `sudo service mysql restart`

Ran `sudo service nginx restart` or `sudo service nginx reload`

Ran `sudo service php7.1-fpm restart`

Ran `sudo service nginx status`

Ran `sudo service mysql status`

Ran `sudo service php7.1-fpm status`

Ran `sudo service sshd restart`

Ran `php -v`

Ran `nginx -v`

Ran `mysqlcheck --repair --all-databases`

### Installation

Clone the `master` branch from Git repo to your local machine. 

Connect to the droplet using S/FTP or SSH.

Navigation to where you have cloned the project files.

Transfer all the project files to the `/var/www/html` folder on the droplet.

Navigation to the `/var/www/html` folder on the droplet.

Rename `*.env.example` to `.env` and replace the following variables with suitable values.

You may need to set permissions on the directories that Laravel needs to write to. 

The following will let Laravel write the storage and bootstrap directories:

`chmod -R o+rw /var/www/html/bootstrap /var/www/html/storage`

Install Composer modules:

`composer update`

Install NPM modules:

`npm install`

Create a new Laravel Application Key:

`php artisan key:generate`

### Data Migration

Take down Laravel:

`php artisan down --message="Upgrading Database" --retry=60`

Run Laravel migration and seed:

`php artisan migrate:fresh --seed`

Run Laravel data import:

`php artisan db:import`

We can also import data from other locations by passing in a `--path` option:

`php artisan db:import --path=/path/to/data.sql`

Bring Laravel back up again:

`php artisan up`

### Running

Build assets (JS, Sass, Fonts, Images etc):

Building for Staging

`npm run dev`

Building for Production

`npm run production`

You should be able to visit the SafetyNet web app at the droplets IP address or domain name (if one was setup) in your browser.

### Miscellanous

#### Sequel Pro for OSX

You can connect to MariaDB database on the droplet using the following details or whatever details you set up in `.env`:

* Host: `DROPLET_IP_ADDRESS`
* Username: `safetynet`
* Password: `safetynet`
* Database: `safetynet`
* Port: `3306`

#### Laravel worker queues

Run Laravel worker queues:

`php artisan queue:work --queue=default --daemon`

For more information on running daemon worker queues, see [https://laravel.com/docs/5.0/queues#daemon-queue-worker](https://laravel.com/docs/5.0/queues#daemon-queue-worker)

Restart Laravel worker queues:

`php artisan queue:restart`

Stopping Laravel worker queues:

TL;DR - There is no safe way! We could use the Artisan command `php artisan down` which will stop all queues.

Regardless of how we stop daemons, I suggest you have a read of [https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent](https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent)

#### Lets Encrypt
