ARG PHP_IMAGE
ARG NGINX_IMAGE
ARG HTOPDF_IMAGE
ARG HTTP_PROXY
ARG HTTPS_PROXY

FROM ${HTOPDF_IMAGE} as wkhtmltopdf
FROM ${PHP_IMAGE} as php_prod

RUN apk add --no-cache --virtual .binary \
        acl \
        fcgi \
        file \
        gettext \
        git \
        nodejs \
        yarn \
    ;

RUN apk add --no-cache --virtual .wkhtml-deps \
        libx11 \
        libxrender \
        libxext \
        libssl1.1 \
        ca-certificates \
        fontconfig \
        freetype \
    	xvfb \
        ttf-dejavu ttf-droid ttf-freefont ttf-liberation \
        && rm -rf /var/cache/apt/* \
    ;

COPY --from=wkhtmltopdf /bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf
# end of wkhtmltopdf installation

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions

RUN install-php-extensions \
        apcu \
        intl \
        opcache \
        zip \
        redis \
        pdo_pgsql \
    ;

COPY --link ./k8s/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck
HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY ./k8s/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY ./k8s/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
COPY ./k8s/php/php-fpm.d/docker.conf /usr/local/etc/php-fpm.d/docker.conf

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

RUN composer install -vvv --prefer-dist --no-dev --no-scripts --no-autoloader --no-cache \
    && rm -rf /root/.composer;

RUN yarn install \
    && yarn run build \
    && yarn cache clean \
    && rm -rf /root/.cache/yarn && rm -rf ./node_modules \
    ;

RUN mkdir -p var/cache var/log; \
    chown -R www-data:www-data var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer dump-env prod; \
    chmod +x bin/console;

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" || true

EXPOSE 9000

CMD ["php-fpm"]

FROM ${NGINX_IMAGE} as nginx_prod

WORKDIR /var/www/html/public

COPY ./k8s/nginx/citoyen.dev.conf /etc/nginx/conf.d/default.conf
COPY --from=php_prod /var/www/html/public /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]
