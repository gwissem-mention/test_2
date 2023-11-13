ARG PHP_IMAGE

FROM ${PHP_IMAGE} as php_prod
ENV APP_ENV=prod

COPY ./k8s/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY ./k8s/php/php-fpm.d/docker.conf /usr/local/etc/php-fpm.d/docker.conf

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./k8s/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

RUN /usr/bin/composer install -vvv --prefer-dist --no-dev --no-scripts --no-autoloader --no-cache \
    && rm -rf /root/.composer/cache;

RUN yarn install \
    && yarn run build \
    && yarn cache clean \
    && rm -rf /root/.cache/yarn \
    && rm -rf ./node_modules

RUN mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    chown -R www-data:www-data var; \
    chmod +x bin/console; \
    sync

COPY --link ./k8s/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

EXPOSE 9000

CMD ["php-fpm"]
