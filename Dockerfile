FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libonig-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql mysqli mbstring curl \
    && rm -rf /var/lib/apt/lists/*

RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

COPY . /var/www/html/

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]