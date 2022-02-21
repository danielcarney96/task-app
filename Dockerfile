FROM php:8.1-fpm

# Set envs
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10" \
    COMPOSER_MEMORY_LIMIT=-1

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Install dependencies
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    build-essential \
    mariadb-client \
    locales \
    zip \
    libzip-dev \
    vim \
    unzip \
    git \
    curl \
    libmcrypt-dev \
    libicu-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    jpegoptim optipng pngquant gifsicle \
    wget \
    python3-pip \
    libltdl-dev dnsutils nmap dnsmasq software-properties-common \
    gnupg2 ca-certificates lsb-release \
    libpq-dev \
    postgresql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install zip exif pcntl opcache soap pgsql pdo_pgsql
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Xdebug
ARG WITH_XDEBUG=0

RUN if [ $WITH_XDEBUG = 1 ] ; then \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.mode = debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi;

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

# Copy existing application files and set directory permissions
COPY --chown=www:www . /var/www

# Set working directory
WORKDIR /var/www

# Install composer packages
ARG COMPOSER_INSTALL=1

RUN if [ $COMPOSER_INSTALL = 1 ] ; then \
        composer install --prefer-dist --optimize-autoloader; \
    fi;

# Install npm packages
ARG NPM_INSTALL=1

RUN if [ $NPM_INSTALL = 1 ] ; then \
        npm install; \
    fi;

# Set ownerships on /var/www directory to be www user
RUN chown -R www:www /var/www

# Copy opcache config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Rename php ini file
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Update php.ini file to add needed overrides
RUN sed -i -e 's/memory_limit = 128M/memory_limit = 512M/g' /usr/local/etc/php/php.ini && \
    sed -i -e 's/\;date.timezone =/date.timezone = Europe\/London/g' /usr/local/etc/php/php.ini && \
    sed -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 20M/g' /usr/local/etc/php/php.ini && \
    sed -i -e 's/\;max_input_vars = 1000/max_input_vars = 5000/g' /usr/local/etc/php/php.ini && \
    sed -i -e 's/\post_max_size = 8M/post_max_size = 100M/g' /usr/local/etc/php/php.ini

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
