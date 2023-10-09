#!/bin/sh
set -e
exec "$@"

MAX_ATTEMPTS_LEFT_TO_REACH_DATABASE=60

if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

  composer run-script --no-dev post-install-cmd

  #if grep -q ^DATABASE_URL= .env; then
  if true; then

    if [ "$( find ./migrations -iname '*.php' -print -quit )" ]; then
      bin/console doctrine:migrations:migrate --no-interaction
    fi
  fi

  setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
  setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

