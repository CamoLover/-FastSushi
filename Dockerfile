FROM php:8.2-fpm

# Installer Composer
RUN apt-get update && apt-get install -y unzip git && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Installer Node.js et npm (version 18 LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@9.8.1
    
# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/