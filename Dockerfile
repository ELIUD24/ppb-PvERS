FROM php:5.6-apache

ENV DEBIAN_FRONTEND=noninteractive

RUN set -eux; \
    sed -ri 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list; \
    sed -ri 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list; \
    sed -ri '/(stretch|jessie)-updates/d' /etc/apt/sources.list; \
    printf 'Acquire::Check-Valid-Until "false";\nAcquire::AllowInsecureRepositories "true";\n' > /etc/apt/apt.conf.d/99archive; \
    apt-get update; \
    apt-get install -y --allow-unauthenticated --no-install-recommends \
        ca-certificates \
        fontconfig \
        xfonts-base \
        xfonts-75dpi \
        wkhtmltopdf; \
    docker-php-ext-install pdo_mysql mbstring; \
    a2enmod rewrite headers; \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint
COPY . /var/www/html

RUN mkdir -p /var/www/html/app/tmp /var/www/html/app/tmp/cache /var/www/html/app/tmp/logs /var/www/html/app/tmp/sessions \
    && chown -R www-data:www-data /var/www/html/app/tmp \
    && chmod -R 775 /var/www/html/app/tmp \
    && chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["apache2-foreground"]
