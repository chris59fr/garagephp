FROM php:8.3-fpm-alpine3.22

#Installer les dépendences et bibliothèques
RUN apk add --no-cache \
        acl \
        icu-dev \
        libzip-dev \
      supervisor \
        nginx \
        git
RUN docker-php-ext-install -j$(nproc) \
        opcache \
        intl \
        zip \
        pdo_mysql



# Copier les fichiers de configuration
COPY ./docker/nginx/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/http.d/default.conf

RUN sed -i 's|user = www-data|user = nginx|g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|group = www-data|group = nginx|g' /usr/local/etc/php-fpm.d/www.conf


## Installation des dependances composer
WORKDIR /var/www/html

COPY --from=composer:2.8.10 /usr/bin/composer /usr/bin/composer
#Copier les fichiers de dépendences et les installer
COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

#copier le reste du code de l'appli
COPY . .

#Executer le dump de l'autoloader de composer (performances)
RUN composer dump-autoload --optimize

#Changer propriétaire des fichiers afin de donner le droit au serveur d'écrire dans les fichiers (EX: logs)
RUN mkdir -p storage/logs && \
    chmod 775 /var/www/html/storage \
    && chown -R nginx:nginx /var/www/html

EXPOSE 80

ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]