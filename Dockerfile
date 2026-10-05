FROM php:8.2-apache

# PDO MySQL driver + CA bundle (needed for TLS connections to cloud databases)
RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates \
    && docker-php-ext-install pdo_mysql \
    && a2enmod headers \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/apache.conf /etc/apache2/conf-available/app.conf
RUN a2enconf app

WORKDIR /var/www/html
COPY . /var/www/html
RUN mkdir -p uploads/banners uploads/docs uploads/users storage \
    && chown -R www-data:www-data uploads storage

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

ENV PORT=80
EXPOSE 80
CMD ["start.sh"]
