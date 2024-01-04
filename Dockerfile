FROM php:8.2-apache

RUN apt-get update -y
RUN apt-get install -y libjpeg-dev mcrypt libfreetype6-dev libmcrypt-dev libjpeg62-turbo-dev libpng-dev curl libcurl4-openssl-dev zip libzip-dev

RUN pecl install mcrypt-1.0.6
RUN docker-php-ext-enable mcrypt
RUN docker-php-ext-install pdo pdo_mysql curl mysqli bcmath
RUN docker-php-ext-install -j$(nproc) iconv
RUN docker-php-ext-install -j$(nproc) gd zip

RUN docker-php-ext-install exif
# Install and enable PHP OPCache
RUN docker-php-ext-install opcache && \
    docker-php-ext-enable opcache
ADD ./docker/php-opcache.ini /usr/local/etc/php/conf.d/zz-nextcloud-opcache.ini
ADD ./docker/php-config.ini /usr/local/etc/php/conf.d/php.ini

RUN apt-get install -y libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Update the default apache site with the config we created.
ADD ./docker/apache-config.conf /etc/apache2/sites-enabled/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod expires
RUN a2enmod deflate
RUN service apache2 restart

# Kafka
RUN apt-get install librdkafka-dev -y
RUN pecl install rdkafka
RUN docker-php-ext-enable rdkafka

# Set working directory
WORKDIR /var/www

