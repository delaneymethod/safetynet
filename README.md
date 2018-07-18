# SafetyNet

[![Build Status](http://165.227.234.36/buildStatus/icon?job=safetynet)](http://165.227.234.36/job/safetyney)

SafetyNet is an internal platform for Survitec Group Sales staff.

## Technologies

* PHP FPM
* MariaDB
* Nginx
* Redis
* Node
* Laravel
* Ubuntu
* Docker
* Jenkins

## Prerequisites

This document focuses on running the SafetyNet web app in a Docker environment and that you have Ubuntu, Docker, Jenkins, Git, Laravel, Redis and MySQL experience/knowledge.

## DEVELOPMENT

This section assumes you already have Docker setup and running locally.

It also assumes that you know the differences between running locally and on a remote Digital Ocean droplet with Docker installed.

Check the currently active set of `DOCKER_` variables, just do:

`env | grep DOCKER`

Also see [https://stackoverflow.com/questions/41685603/reset-docker-machine-to-run-docker-commands-on-my-local-machine](https://stackoverflow.com/questions/41685603/reset-docker-machine-to-run-docker-commands-on-my-local-machine)

It is important that you are not connected to a remote Docker machine and that your Docker env variables are set to your local machine.

### Installation

Clone the `master` branch from Git repo to your local machine. 

Navigation to where you have cloned the project files.

Rename `*.env.example` to `.env` and replace the following variables with suitable values.

Start all containers:

`./development up -d`

Install Composer modules:

`./development composer update`

Install NPM modules:

`./development npm install`

Create a new Laravel Application Key:

`./development artisan key:generate`

## Data Migration

Take down Laravel (Used during migration):

`./development artisan down --message="Upgrading Database" --retry=60`

Run Laravel migration and seed:

`./development artisan migrate:fresh --seed`

We can also import data from other locations by passing in a `--path` option:

`./development artisan db:import --path=/path/to/data.sql`

Run Laravel data import:

`./development artisan db:import`

Bring Laravel back up again:

`./development artisan up`

## Running

Build and watch assets (JS, Sass, Fonts, Images etc):

Building for Development

`./development npm run watch`

You should be able to visit the SafetyNet web app at [http://localhost](http://localhost) in your browser.

### Miscellanous

#### Sequel Pro for OSX

You can connect to the MariaDB database running within a Docker container using the following details or whatever details you set up in `.env`:

* Host: `0.0.0.0`
* Username: `safetynet`
* Password: `safetynet`
* Database: `safetynet`
* Port: `3306`

You can also connect and select a database, show tables and run queries within the Docker container:

`docker exec -it safetynet_mariadb_1 bash` or whatever the container name is. See `./development ps` for more information.

`mysql -u root -p`

Password: `safetynet`

`show databases;`

`quit;`

Then exit out of the container again:

`exit;`

#### Laravel worker queues

Run Laravel worker queues:

`./development artisan queue:work --queue=default --daemon`

For more information on running daemon worker queues, see [https://laravel.com/docs/5.0/queues#daemon-queue-worker](https://laravel.com/docs/5.0/queues#daemon-queue-worker)

Restart Laravel worker queues:

`./development artisan queue:restart`

Stopping Laravel worker queues:

`docker stop safetynet_phpfpm_run_1` or whatever the container name is. See `./development ps` for more information.

TL;DR - There is no safe way! We could use the Artisan command `./development artisan down` which will stop and remove all docker containers.

Regardless of how we stop daemons, I suggest you have a read of [https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent](https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent)

#### Redis

You can connect to the Redis database using the following details or whatever details you set up in `.env`:

All Redis GUI&#39;s are supported.

#### Horizon

Horizon allows you to easily monitor key metrics of your queue system such as job throughput, runtime, and job failures.

Start Horizon:

`./development artisan horizon`

You should be able to visit the app at [http://localhost/horizon/](http://localhost/horizon/)

For more information about Horizon, see: [https://laravel.com/docs/master/horizon](https://laravel.com/docs/master/horizon)

#### Docker Commands

Start all containers:

`./development up -d`

List running containers:

`./development ps`

Stop all running containers:

`./development down`

List volumes:

`./development volumes`

List networks:

`./development networks`

Clear Laravel log:

`./development logs`

## PRODUCTION

This section assumes you already have a Digital Ocean account. 

Please note other providers can be used such as AWS or Azure. You would obviously have to swap out specific Digital Ocean values.

### Droplet Configuration

Create a new droplet with Docker installed:

`DIGITIAL_OCEAN_API_KEY=XXXX ./production create 2gb lon1 web-01.safetynet.survitecgroup.com`

I would recommend going for more memory though, but for the basic droplet, 512MB is enough with some tweaks.

Also see [https://docs.docker.com/machine/drivers/digital-ocean/#usage](https://docs.docker.com/machine/drivers/digital-ocean/#usage)

If for some reason your new droplet is not the active host, you’ll need to run `./production env web-01.safetynet.survitecgroup.com`.

Followed by `eval $(docker-machine env web-01.safetynet.survitecgroup.com)` to connect to the correct Docker machine env.

To test if this has worked, you can run `docker images`, which should be blank. 

If any images are listed, you’re probably still connected to the local machine (E.g your Mac) running a Docker daemon.

Also see: [https://docs.docker.com/machine/examples/ocean/](https://docs.docker.com/machine/examples/ocean/)

If for some reason, Docker on the new droplet did not start automatically, you can run:

`./production start web-01.safetynet.survitecgroup.com`

So now we have a running droplet with Docker installed.

However, the basic Digital Ocean 512MB droplet we setup for demo purposes will have a lack of memory or swap as mentioned above for what we need, so I suggest you read over this article and increase. 

You can SSH into the droplet and make your changes outlined in [https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04](https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04)

`./production ssh web-01.safetynet.survitecgroup.com`

To stop running Docker on your new droplet locally run:

`./production stop web-01.safetynet.survitecgroup.com`

To remove Docker on your new droplet run. Note this will also delete the droplet:

`./production rm web-01.safetynet.survitecgroup.com`

Now if you go back to your Digital Ocean account, the droplet no longer exists.

Reset Docker variables:

`eval $(docker-machine env -u)`
		
To re-create the SSL Cert run:

`./production ssl web-01.safetynet.survitecgroup.com`

### Deployment

This step is to allow us to deploy new changes keeping 99% up time.

The process we are adopting for updating the SafetyNet Production server is as follows:

* Developer make their changes locally in your development Docker environment.
* Developer commits their code changes (squash commit into a single commit is preferred) and pushes their working branch to Gitlab for approval.
* Developer will create a new pull request against the `master` branch on Gitlab.
* Someone will review code changes and approve.
* Someone will merge the developers branch into the `master` branch on Gitlab.

The last step triggers a new "build" on the Jenkins CI/CD server, where all the Docker images are refreshed, code pulled from Gitlab and everything gets tested.

If everything passes, the Jenkins CI/CD server then creates a Production ready Docker image with all the latest code inside it and this image then gets pushed to the SafetyNet Docker registry.

Jenkins CI/CD then calls a deployment script on the Production server which pulls down the latest Production ready Docker image from the Docker registry and replaces everything, restarting Nginx Proxy/Load Balancer in the process.

#### Nginx Proxy/Load Balancer Configuration

We setup a new Nginx Proxy/Load Balancer so we have little or not down time during a deployment.

We need to SSH into the Production droplet: `./production ssh web-01.safetynet.survitecgroup.com`

We need to run `./production pull` on our local machine again to update the Docker images on the Production droplet so we are using latest images for each container.

We then run: `docker pull nginx:alpine` to get a basic Nginx server image.

We go to the `cd /opt` directory

We create a new folder called `conf.d` with a file called `default.conf`:

`mkdir conf.d && touch conf.d/default.conf`

We then create a new container name: `echo "safetynet_nginx_phpfpm_`date +"%s"`" # e.g. safetynet_nginx_phpfpm_1487724356`

We then copy and paste the output into `/opt/conf.d/default.conf`, replacing the `server` value (not the :80) and save the file again:

```
upstream app {
	server safetynet_nginx_phpfpm_1516113548:80;
}

server {
	listen 80;

	location / {
		rewrite ^ https://$host$request_uri? permanent;
	}	
}

server {
	client_max_body_size 600m;
	client_header_buffer_size 1k;
	client_body_buffer_size 256k;
	large_client_header_buffers 4 16k;
	
	listen 443 default_server;
	
	root /var/www/html;
	
	charset utf-8;
	
	server_name _;
	server_tokens off;
	
	add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload";
	add_header X-XSS-Protection "1; mode=block" always;
	add_header X-Content-Type-Options "nosniff" always;
	add_header X-Frame-Options "DENY" always;
	add_header Content-Security-Policy "frame-src 'self' https://persona.yammer.com https://login.microsoftonline.com https://www.yammer.com https://web.microsoftstream.com; default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google-analytics.com https://survitecgroup.sharepoint.com; img-src 'self' https://www.gravatar.com https://www.google-analytics.com https://safetynet-survitecgroup.ams3.digitaloceanspaces.com https://survitecgroup.sharepoint.com https://via.placeholder.com; style-src 'self' 'unsafe-inline'; font-src 'self' data:; form-action 'self'; upgrade-insecure-requests;" always;
	add_header Referrer-Policy "strict-origin-when-cross-origin" always;
	
	ssl on;
	ssl_certificate /etc/letsencrypt/live/safetynet.survitecgroup.com/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/safetynet.survitecgroup.com/privkey.pem;
	ssl_session_cache shared:SSL:50m;
	ssl_buffer_size 8k;
	ssl_dhparam /etc/ssl/certs/dhparam-2048.pem;
	ssl_protocols TLSv1.2 TLSv1.1 TLSv1;
	ssl_prefer_server_ciphers on;
	ssl_ciphers ECDH+AESGCM:ECDH+AES256:ECDH+AES128:DH+3DES:!ADH:!AECDH:!MD5;
	ssl_ecdh_curve secp384r1;
	ssl_session_tickets off;
	ssl_stapling on;
	ssl_stapling_verify on;
	
	resolver 8.8.8.8 8.8.4.4;
	
	location / {
		proxy_pass http://app;
		proxy_set_header Host $host;
		proxy_set_header X-Real-IP $remote_addr;
		proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
		proxy_set_header X-Forwarded-Proto $scheme;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection "upgrade";
		proxy_buffer_size 128k;
		proxy_buffers 256 16k;
		proxy_redirect http:// https://;
		proxy_intercept_errors on;
		proxy_buffering on;
		proxy_busy_buffers_size 256k;
		proxy_temp_file_write_size 256k;
		proxy_max_temp_file_size 0;
		proxy_read_timeout 300;
		proxy_http_version 1.1;		
	}
}
```

Next we create a new network, if a network doesn't already exist: `docker network create safetynet_network`.

We then start the main Nginx / PHP-FPM container:

`docker run -d --network=safetynet_network --restart=always --name="safetynet_nginx_phpfpm_1516113548" survitecgroup/safetynet`

Note nothing happens for now. Nothing is listening on port 80 and 443 so we spin up our Nginx container:

`docker run -d --name=safetynet_nginx_proxy --network=safetynet_network --restart=always -v /opt/conf.d:/etc/nginx/conf.d -v /opt/dhparam-2048.pem:/etc/ssl/certs/dhparam-2048.pem -v /docker-volumes/etc/letsencrypt/live/safetynet.survitecgroup.com/fullchain.pem:/etc/letsencrypt/live/safetynet.survitecgroup.com/fullchain.pem -v /docker-volumes/etc/letsencrypt/live/safetynet.survitecgroup.com/privkey.pem:/etc/letsencrypt/live/safetynet.survitecgroup.com/privkey.pem -p 80:80 -p 443:443 nginx:alpine`

So basically what we have now is an Nginx container acting as a Proxy/Load Balancer accepting traffic and forwarding this traffic to our main Nginx / PHP-FPM container.

Now we create our uploads folder structure that will be mounted against the Laravel uploads folder when the container is started by running:

`cd /`
`cd var`
`mkdir www`
`cd www`
`mkdir html`
`cd html`
`mkdir public`
`cd public`

Within the `public` folder, we need to create an `uploads` folder and a `.well-known` folder:

`mkdir uploads`
`cd uploads`
`mkdir supporting-files`
`cd ..`
`mkdir .well-known`
`cd .well-known`
`mkdir acme-challenge`

This creates the same structure used within Laravel for file uploads and matches our deploy script volume mounting flag below. We mount this volume to keep file uploads persistence during deployments as otherwise the uploads folder would be wiped and all files uploaded lost.

Lets make sure we set the correct permissions too by running:

`chown -R www-data:www-data /var/www`

If the recursive `chown` didn't work, do each folder:

`chown www-data:www-data /var/www`
`chown www-data:www-data /var/www/html`
`chown www-data:www-data /var/www/html/public`
`chown www-data:www-data /var/www/html/public/uploads`
`chown www-data:www-data /var/www/html/public/uploads/supporting-files`
`chown www-data:www-data /var/www/html/public/.well-known`
`chown www-data:www-data /var/www/html/public/.well-known/acme-challenge`

Now we create our `deploy` script by running:

`touch deploy && chmod +x deploy && pico deploy`

Paste the contains into the script and save:

```
#!/usr/bin/env bash

########################################################
#
# Get info on currently running "safetynet_nginx_phpfpm" container
#
APP_CONTAINER=$(docker ps -a -q --filter="name=safetynet_nginx_phpfpm")

QUEUE_CONTAINER=$(docker ps -a -q --filter="name=safetynet_queue")

NEW_CONTAINER="safetynet_nginx_phpfpm_`date +"%s"`"

########################################################
#
# Deploy a new container
#

# Docker Registry login
docker login -u survitecgroup -p survitecgroup

# Pull latest
docker pull survitecgroup/safetynet

# Don't deploy if latest image is running
RUNNING_IMAGE=$(docker inspect $APP_CONTAINER | jq ".[0].Image")

CURRENT_IMAGE=$(docker image inspect survitecgroup/safetynet:latest | jq ".[0].Id")

if [ "$CURRENT_IMAGE" == "$RUNNING_IMAGE" ]; then
    echo ">>> Most recent image is already in use"
    
    exit 0
fi

# Stop the queue server first, we can afford to stop it for a bit
docker stop $QUEUE_CONTAINER

docker rm -v $QUEUE_CONTAINER

echo "Removed old queue container $QUEUE_CONTAINER"

# Start new instance
NEW_APP_CONTAINER=$(docker run -d --network=safetynet_network --restart=unless-stopped --name="$NEW_CONTAINER" -v /var/www/html/public/uploads:/var/www/html/public/uploads -v /var/www/html/public/.well-known:/var/www/html/public/.well-known survitecgroup/safetynet)

NEW_QUEUE_CONTAINER=$(docker run -d --network=safetynet_network --name="safetynet_queue" --restart=unless-stopped -w /var/www/html survitecgroup/safetynet php artisan queue:work --queue=default --daemon)

# Wait for processes to boot up
sleep 5

echo "Started new container $NEW_APP_CONTAINER"

# Update Nginx
sed -i "s/server safetynet_nginx_phpfpm_.*/server $NEW_CONTAINER:80;/" /opt/conf.d/default.conf

# Config test Nginx
docker exec safetynet_nginx_proxy nginx -t

NGINX_STABLE=$?

if [ $NGINX_STABLE -eq 0 ]; then
	# Reload Nginx
	docker kill -s HUP safetynet_nginx_proxy

	# Stop older instance
	docker stop $APP_CONTAINER
	
	# Remove older instance
	docker rm -v $APP_CONTAINER
	
	echo "Removed old container $APP_CONTAINER"

	# Cleanup, if any dangling images
	DANGLING_IMAGES=$(docker image ls -f "dangling=true" -q)
	
	if [ ! -z "$DANGLING_IMAGES" ]; then
		docker image rm $(docker image ls -f "dangling=true" -q)
	fi
else
	echo "ERROR: Nginx configuration test failed!"
	
	exit 1
fi
```

To run a deployment, we can now simply run: `./deploy` however, we will do this automatically with our Jenkins build script. These steps that you just ran was to get everything setup! :)

#### Jenkins Configuration

We need a new droplet to install Jenkins on so we can handle all the testing and building of our Production images.

We do the same steps as we did for the Production droplet itself - we create a new droplet as our 1st step.

Next we need to install some basics: 

`sudo apt-get update`

`sudo apt-get install -y curl wget unzip htop ntp software-properties-common`

Then we install Jenkins:

`wget -q -O - https://pkg.jenkins.io/debian/jenkins-ci.org.key | sudo apt-key add -`

`echo 'deb http://pkg.jenkins.io/debian-stable binary/' | sudo tee /etc/apt/sources.list.d/jenkins.list`

`sudo apt-get update`

`sudo apt-get install -y jenkins`

`sudo service jenkins status`

Then test it:

`curl localhost:8080`

Next we need to install Nginx and use that as our HTTP entry point for Jenkins so we can proxy requests over to Jenkins.

`sudo add-apt-repository -y ppa:nginx/stable`

`sudo apt-get update`

`sudo apt-get install -y nginx`

We now need to configure Nginx:

`sudo rm /etc/nginx/sites-enabled/default`

`sudo pico /etc/nginx/sites-available/jenkins`

Make it like so:

```
server {
	listen 80 default_server;
	server_name localhost;
	
	location / {
		proxy_pass http://localhost:8080;
		proxy_set_header Host $host;
		proxy_set_header X-Real-IP $remote_addr;
		proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
		proxy_connect_timeout 150;
		proxy_send_timeout 100;
		proxy_buffers 4 32k;
		client_max_body_size 100m;
		client_body_buffer_size 256k;
	}
}
```

Then enable it:

`sudo ln -s /etc/nginx/sites-available/jenkins /etc/nginx/sites-enabled/jenkins`
    
Test and reload Nginx:

`sudo service nginx configtest`

`sudo service nginx reload`

Jenkins should now be web accessible!

Now we need to unlock Jenkins by running:

`sudo cat /var/lib/jenkins/secrets/initialAdminPassword`

Use that password in the browser to create a new user and install the suggested plugins. Note because this project was setup in Gitlab, we need to install all the Gitlab related plugins. Do not get these confused with the Github plugins!

We then install Docker and Docker compose

`curl -sSL https://get.docker.com/ | sudo sh`

Ensure "jenkins" user can use it without "sudo", since we'll be automating jenkins with Docker

`sudo usermod -aG docker jenkins`

`docker ps`

`docker docker ps`

`sudo -u jenkins docker ps`

Install Docker Compose:

`sudo su`

```
curl -L https://github.com/docker/compose/releases/download/1.10.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
```

`chmod +x /usr/local/bin/docker-compose`

`exit`

We need to restart Jenkins so it picks up the addition of group docker to user jenkins:

`sudo service jenkins restart`
 
If you would like to read up about installing Jenkins on Ubuntu, see: 

[https://wiki.jenkins-ci.org/display/JENKINS/Installing+Jenkins+on+Ubuntu](https://wiki.jenkins-ci.org/display/JENKINS/Installing+Jenkins+on+Ubuntu)
   
#### Jenkins Build/Worker

The next step is to setup a new item in Jenkins to run our tests and build our image, using the `Jenkinsfile` found in our Git project.
    
I found a happy video tutorial that explains all these steps here: 

[https://course.shippingdocker.com/lessons/module-9/configuring-github-auth](https://course.shippingdocker.com/lessons/module-9/configuring-github-auth)

But as per the README prerequisites, Jenkins knowledge is required so you should know how to set up:

* GitLab for Authentication in Configure Global Security.
* Server Git Access as you will need access to be able to run git commands against the Git repository.
* Jenkins Pipeline.

If not, please refer the link above. I guess if you are in Doubt, look at the config setup here: [http://46.101.35.112/](http://46.101.35.112/) - This was the Glaze Digital Jenkins droplet setup during development.

#### Docker Compose

Now that we have our Production droplet setup, configured with Docker, Docker machine, Docker compose, a Jenkins droplet setup with a deployment workflow using Nginx Proxy/Load Balancer, we are ready to cover Docker compose commands and actually running these on our Production server.

We can run Docker compose commands locally while we are connected to the remote droplet just like we could with Docker machine.

Docker compose is reading the `docker-compose.production.yml` file so it will pull down all the required Docker images and fire everything up.

Because we are dealing with a Production ready Docker image with the codebase self contained inside so all we need to do is run `./production up` to start up everything.

If you run `./production ps`, you should get back a blank list of running containers on the droplet.

To stop all running containers on the droplet, you can run `./production down`.

If you have any issues running Docker machine commands on the remote droplet, locally, SSH (`./production ssh web-01.safetynet.survitecgroup.com`) into the droplet and run the same Docker commands like:

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan version:refresh`

### Deployments

Once the production ready image has been deployed, we need to run the version / build command and any database import scripts to update the schema and/or data.

To update the version / build information, run: 

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan version:refresh`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan version:refresh`

Please note that you will be required to login to Gitlab using your username and personal access token as the password.

### Data Migration

Take down Laravel:

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan maintenance:start`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan maintenance:start`

Run Laravel migration and seed if a new database. Do not run this on Production:

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan migrate:fresh --seed`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan migrate:fresh --seed`

Run Laravel data import (be careful with this command as if your .sql is not correct, you could wipe your DB data):

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan db:import`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan db:import`

We can also import data from other locations by passing in a `--path` option:

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan db:import --path=/path/to/data.sql`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan db:import --path=/path/to/data.sql`

Bring Laravel back up again:

`./production compose run --rm -w /var/www/html nginx_phpfpm php artisan maintenance:stop`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan maintenance:stop`

### Worker Queues

Run Laravel worker queues:

`./production compose run --rm --network=safetynet_network --restart=unless-stopped --name="safetynet_queue" -w /var/www/html nginx_phpfpm php artisan queue:work --queue=default --daemon`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan queue:work --queue=default --daemon`

For more information on running daemon worker queues, see [https://laravel.com/docs/5.0/queues#daemon-queue-worker](https://laravel.com/docs/5.0/queues#daemon-queue-worker)

Restart Laravel worker queues:

`./production compose run --rm --network=safetynet_network --restart=unless-stopped --name="safetynet_queue" -w /var/www/html nginx_phpfpm php artisan queue:restart`

or 

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network survitecgroup/safetynet:latest php /var/www/html/artisan queue:restart`

Stopping Laravel worker queues by SSH'ing into the droplet and run:

`docker stop safetynet_nginx_phpfpm_1` or whatever the container name is. See `docker ps -a` for more information.

TL;DR - There is no safe way! We could use the Artisan command `./production compose run --rm --network=safetynet_network -w /var/www/html nginx_phpfpm php artisan down` (or `docker run -it...`) which will stop and remove all docker containers.

Regardless of how we stop daemons, I suggest you have a read of [https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent](https://laracasts.com/discuss/channels/general-discussion/stop-the-daemon-urgent)

### Running

All the assets have been already build in our Production ready image but if we ever needed to rebuild them again, we can do the following:

Build assets (JS, Sass, Fonts, Images etc):

`./production compose run --rm -w /var/www/html node npm run production`

or

`docker run -it --rm -v /srv/application:/opt -w /opt --network=safetynet_network delaneymethod/node:latest node npm run production`

Viewing logs:

`./production exec -it safetynet_nginx_phpfpm_1 tail -f /var/www/html/storage/logs/laravel.log`

You should be able to visit the SafetyNet web app at the droplets IP address or domain name (if one was setup) in your browser.

### Miscellanous

#### Sequel Pro for OSX

You can connect to MariaDB database on the droplet running Docker container using the same details or whatever details you set up in `.env`, except for the `host`. We need to change this to the IP address of the droplet. 

We can grab the IP of the droplet by logging into DigitalOcean dashboard or running:

`./production ip web-01.safetynet.survitecgroup.com`

Now that you have the IP, we just set:

* Host: `DROPLET_IP_ADDRESS`
* Username: `safetynet`
* Password: `safetynet`
* Database: `safetynet`
* Port: `3306`

#### Redis

You can connect to the Redis database using the following details or whatever details you set up in `.env`:

All Redis GUI&#39;s are supported.

#### Horizon

Horizon allows you to easily monitor key metrics of your queue system such as job throughput, runtime, and job failures.

Start Horizon:

`./production compose run --rm --network=safetynet_network -w /var/www/html nginx_phpfpm php artisan horizon`

You should be able to visit the app at [http://localhost/horizon/](http://localhost/horizon/)

For more information about Horizon, see: [https://laravel.com/docs/master/horizon](https://laravel.com/docs/master/horizon)

#### Lets Encrypt

The original guide used was this: [https://www.humankode.com/ssl/how-to-set-up-free-ssl-certificates-from-lets-encrypt-using-docker-and-nginx](https://www.humankode.com/ssl/how-to-set-up-free-ssl-certificates-from-lets-encrypt-using-docker-and-nginx)

But since we already have our Nginx Proxy/Load Balancer and Nginx PHP-FPM containers setup, we kinda skipped to the section where we run the docker images:

Generate the certificates: `sudo docker run -it --rm -v /docker-volumes/etc/letsencrypt:/etc/letsencrypt -v /docker-volumes/var/lib/letsencrypt:/var/lib/letsencrypt -v /docker-volumes/var/log/letsencrypt:/var/log/letsencrypt -v /var/www/html/public:/data/letsencrypt certbot/certbot certonly --webroot --email mark@glazedigital.com --agree-tos --no-eff-email --webroot-path=/data/letsencrypt -d safetynet.survitecgroup.com`

View the certificates: `sudo docker run --rm -it --name certbot -v /docker-volumes/etc/letsencrypt:/etc/letsencrypt -v /docker-volumes/var/lib/letsencrypt:/var/lib/letsencrypt -v /var/www/html/public:/data/letsencrypt certbot/certbot certificates`

Generate a 2048 bit DH Param file: `sudo openssl dhparam -out /opt/dhparam-2048.pem 2048`

Add a crontab to renew the certificates: `sudo crontab -e`

Place the following at the end of the file, then close and save it. 

`0 23 * * * docker run --rm -it --name certbot -v /docker-volumes/etc/letsencrypt:/etc/letsencrypt -v /docker-volumes/var/lib/letsencrypt:/var/lib/letsencrypt -v /var/www/html/public:/data/letsencrypt -v /docker-volumes/var/log/letsencrypt:/var/log/letsencrypt certbot/certbot renew --quiet`

This command will run every night at 23:00.
