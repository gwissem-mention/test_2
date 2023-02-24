#!/bin/sh
set -e

psql postgresql://${POSTGRES_REFERENTIAL_USER:-symfony}:${POSTGRES_REFERENTIAL_PASSWORD:-ChangeMe}@${POSTGRES_REFERENTIAL_HOST:-database-citoyen}:5432/${POSTGRES_REFERENTIAL_DB:-reference} <<-EOSQL
  create extension if not exists "unaccent";
EOSQL
