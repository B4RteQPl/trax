FROM php:8.1-fpm

# Add user 1000 to www-data group.
RUN usermod -u 1000 www-data

# Update packages.
RUN apt-get --allow-releaseinfo-change update
# Install "ps"
RUN apt-get install -y procps
# Install some extensions.
RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev wget unzip openssh-client gnupg lsb-release libmagickwand-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql \
    && docker-php-ext-install zip xml \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Install composer.
RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Set timezone.
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/UTC /etc/localtime
RUN "date"

# Copy project code.
ADD ./ /var/www/trax
RUN chmod 775 /var/www/trax

# Set permissions to cache and log folder.
RUN mkdir -p /var/www/trax/var/cache
RUN mkdir -p /var/www/trax/var/log
RUN chmod -R 777 /var/www/trax/var/cache /var/www/trax/var/log

# Install node.js
RUN echo "NODE_ENV=development" >> /etc/environment
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | apt-key add -
#RUN curl -sL https://deb.nodesource.com/setup_lts.x | bash
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash
RUN apt-get install -y nodejs

# Install vendor.
WORKDIR /var/www/trax

EXPOSE 3000
