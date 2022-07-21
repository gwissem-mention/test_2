ARG CADDY_VERSION=2

FROM caddy:${CADDY_VERSION}-builder-alpine AS symfony_caddy_builder

RUN xcaddy build

FROM caddy:${CADDY_VERSION} AS symfony_caddy

COPY --from=symfony_caddy_builder /usr/bin/caddy /usr/bin/caddy
COPY Caddyfile /etc/caddy/Caddyfile
