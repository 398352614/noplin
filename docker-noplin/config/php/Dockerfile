FROM php:8.1.0RC4-fpm-buster

COPY ./config/php/sources.list.buster /etc/apt/sources.list

RUN apt-get update \
&& apt-get install -y \
        sudo \
        vim \
        curl \
        wget \
        git \
        zip \
        net-tools \
        procps \
        zlib1g-dev \
        libpng-dev \
        libmcrypt-dev \
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
        libmemcached-dev \
        #gd库依赖
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        #表格依赖
        libxext-dev \
        libfontconfig1-dev \
        libxrender-dev \
        libzip-dev \
&& apt-get install -y --no-install-recommends libpq-dev

RUN docker-php-ext-install -j$(nproc) iconv \
&& docker-php-ext-install mysqli pdo_mysql pcntl bcmath \
&& docker-php-ext-install zip \
&& docker-php-ext-configure gd  --with-jpeg --with-freetype \
&& docker-php-ext-install -j$(nproc) gd

#将宿主机的php配置复制到容器中
COPY ./config/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./config/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#安装redis扩展
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis



RUN chown -R www-data:www-data /var/www
WORKDIR /var/www/html/api-noplin

#composer安装
RUN wget https://mirrors.aliyun.com/composer/composer.phar -O /usr/bin/composer \
&& chmod +x /usr/bin/composer

