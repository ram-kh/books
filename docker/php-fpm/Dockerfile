FROM php:8.0-fpm-alpine3.16

ARG UID="1000"
ARG GID="1000"

RUN apk add --no-cache linux-headers $PHPIZE_DEPS \
            libjpeg-turbo-dev \
            libpng-dev \
            libwebp-dev \
            freetype-dev \
    && pecl install xdebug-3.3.1 \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && docker-php-ext-enable pdo  xdebug pdo_mysql gd \
    && rm -rf /tmp/pear \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* \
    \
    && mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini \
    && sed -i "s/short_open_tag = Off/short_open_tag = On/" /usr/local/etc/php/php.ini \
    \
    && addgroup -g ${GID} app && addgroup www-data app \
    && adduser -u ${UID} -s /bin/sh -D -G app app

COPY ./php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cache

COPY ./php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9123 || exit 1