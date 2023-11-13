ARG PHP_IMAGE
ARG NGINX_IMAGE=owasp/modsecurity-crs:3.3.5-nginx-alpine-202310170110
ARG HTTP_PROXY
ARG HTTPS_PROXY

FROM ${PHP_IMAGE} as php_prod
FROM ${NGINX_IMAGE}

COPY ./k8s/nginx/citoyen.dev.conf /etc/nginx/conf.d/default.conf
COPY --from=php_prod /var/www/html/public /var/www/html/public

WORKDIR /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]
