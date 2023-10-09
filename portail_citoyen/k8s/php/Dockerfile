ARG PHP_IMAGE
ARG HTTP_PROXY
ARG HTTPS_PROXY

FROM ${PHP_IMAGE} as php_prod
ENV APP_ENV=prod

COPY ./k8s/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY ./k8s/php/php-fpm.d/docker.conf /usr/local/etc/php-fpm.d/docker.conf

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./k8s/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

RUN /usr/bin/composer install -vvv --prefer-dist --no-dev --no-scripts --no-autoloader --no-cache

RUN yarn install
RUN yarn run build

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer dump-env prod; \
    chmod +x bin/console; sync

EXPOSE 9000

CMD ["php-fpm"]
