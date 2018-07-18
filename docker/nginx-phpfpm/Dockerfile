FROM ubuntu:16.04

MAINTAINER Sean Delaney <hello@delaneymethod.com>

RUN apt-get update && apt-get install -y locales && locale-gen en_GB.UTF-8

ENV LANG en_GB.UTF-8
ENV LANGUAGE en_GB:en
ENV LC_ALL en_GB.UTF-8

RUN apt-get update && apt-get install -y nano nginx jpegoptim optipng pngquant gifsicle curl zip unzip git gettext-base software-properties-common supervisor sqlite3 && add-apt-repository -y ppa:ondrej/php && apt-get update && apt-get install -y php7.1-fpm php7.1-cli php7.1-common php7.1-mcrypt php7.1-gd php7.1-mysql php7.1-pgsql php7.1-sqlite php7.1-sqlite3 php7.1-imap php7.1-memcached php7.1-mbstring php7.1-imagick php7.1-xml php7.1-zip php7.1-curl php7.1-xdebug && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && mkdir /run/php && apt-get remove -y --purge software-properties-common && apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

COPY nginx.conf /etc/nginx/

COPY default /etc/nginx/sites-available/default

COPY www.conf /etc/php/7.1/fpm/pool.d/www.conf

COPY php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf

COPY php.ini /etc/php/7.1/fpm/php.ini

COPY xdebug.ini /etc/php/7.1/mods-available/xdebug.ini

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

CMD ["start-container"]

COPY packaged /var/www/html

RUN chown -R www-data: /var/www/html
