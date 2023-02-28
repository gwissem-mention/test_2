#!/bin/sh
set -e

psql postgresql://${POSTGRES_USER:-symfony}:${POSTGRES_PASSWORD:-ChangeMe}@${POSTGRES_HOST:-database-agent}:5432/${POSTGRES_DB:-app} <<-EOSQL
  create extension if not exists "unaccent";
EOSQL
