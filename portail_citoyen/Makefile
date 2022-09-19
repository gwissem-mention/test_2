.DEFAULT_GOAL := help

PHP			:= @php
COMPOSER	:= @composer
CONSOLE		:= @bin/console
SF_CLI		:= @symfony
YARN		:= @yarn

NPROC	:= 1
OS		:= $(shell uname)

ifeq ($(OS),Linux)
	NPROC := $(shell nproc)
else ifeq ($(OS),Darwin)
	NPROC := $(shell sysctl -n hw.ncpu)
endif

ifndef CI_JOB_ID
	GREEN	:= \033[32m
	YELLOW	:= \033[33m
	WHITE	:= \033[107m
	RESET	:= \033[39m
	TARGET_MAX_CHAR_NUM=30
endif

APP_ENV?=dev

## Display help
help:
	@echo "${GREEN}Plainte en ligne - Portail Citoyen${RESET} https://citoyen.pel.localhost"
	@awk '/^[a-zA-Z\-_0-9]+:/ { \
			helpMessage = match(lastLine, /^## (.*)/); \
			if (helpMessage) { \
				helpCommand = substr($$1, 0, index($$1, ":")); helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
				printf "  ${YELLOW}%-$(TARGET_MAX_CHAR_NUM)s${RESET} ${GREEN}%s${RESET}\n", helpCommand, helpMessage; \
			} \
			isTopic = match(lastLine, /^###/); \
	    if (isTopic) { printf "\n%s\n", $$1; } \
	} { lastLine = $$0 }' $(MAKEFILE_LIST)

#################################
Project:

## Fix linux permissions
permfix:
	@$(eval uid ?=)
	@$(eval gid ?=)
	@find . -path ./public -prune -o -exec chown $(uid):$(gid) {} +;

## Run symfony's console command
console:
	@$(eval cmd ?=)
	@$(CONSOLE) $(cmd)

#################################
Composer:

## Run composer for citoyen container, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
composer:
	@$(eval c ?=)
	@$(COMPOSER) $(c)

## Install php dependencies for dev
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader
vendor: composer

## Update composer dependencies
vendor-update: c=update --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader
vendor-update: composer

## Show recipes
recipes: c=recipes
recipes: composer

## Synchronize the recipes
sync-recipes: c=sync-recipes
sync-recipes: composer

## Show recipes
recipes-update: c=recipes:update
recipes-update: composer

## Install php tools
tools-install:
	@$(COMPOSER) install --working-dir=tools
	@wget https://phar.phpunit.de/phpcpd.phar -O tools/phpcpd.phar

#################################
Yarn:

## Run yarn for citoyen container, pass the parameter "c=" to run a given command, example: make yarn c='install'
yarn:
	@$(eval c ?=)
	@$(YARN) $(c)

## Install nodes packages
install: c=install
install: yarn

## Build dev nodes packages and watch
watch: c=watch
watch: yarn

## Build prod nodes packages
build: c=build
build: yarn

## Build ci nodes packages
build-ci: c=build-ci
build-ci: yarn

## Build dev nodes packages
dev: c=dev
dev: yarn

#################################
Doctrine:

## Drop, create db and create tables
db-create:
	@$(eval APP_ENV ?=)
	@$(CONSOLE) doctrine:database:drop --force --if-exists --env=$(APP_ENV)
	@$(CONSOLE) doctrine:database:create --env=$(APP_ENV)
	@$(CONSOLE) doctrine:migrations:migrate -n --allow-no-migration --env=$(APP_ENV)

db-ci-setup:
	@$(eval APP_ENV ?=)
	@$(CONSOLE) doctrine:database:drop --force --if-exists --env=$(APP_ENV)
	@$(CONSOLE) doctrine:database:create --env=$(APP_ENV) --if-not-exists
	@$(CONSOLE) doctrine:schema:update --force

#################################
QA:

## Run php-cs-fixer
cs:
	@tools/vendor/bin/php-cs-fixer fix --dry-run --diff --config .php-cs-fixer.dist.php

## Run php-cs-fixer
cs-fix:
	@tools/vendor/bin/php-cs-fixer fix --diff --config .php-cs-fixer.dist.php

## Run php magic number detector
mnd:
	@tools/vendor/bin/phpmnd src

## Run phpstan
phpstan:
	@tools/vendor/bin/phpstan analyse -c .phpstan.neon

## Run php assumptions
assumption:
	@tools/vendor/bin/phpa src

## Run infection (mutation testing)
infection:
	XDEBUG_MODE=coverage tools/vendor/bin/infection run -j$(NPROC) -s --ignore-msi-with-no-mutations --no-progress -c infection.json

## Run Symfony security checker
security:
	@$(SF_CLI) security:check

## Run PHP Copy/Paste Detector
cpd:
	@$(PHP) tools/phpcpd.phar src

#################################
Tests:

## Run phpunit tests
unit:
	@bin/phpunit --no-coverage --order-by random --process-isolation -vvv --stop-on-failure

## Run behat tests
behat:
	@vendor/bin/behat -c behat.yml.dist --profile func

## Run e2e behat tests
e2e:
	@vendor/bin/behat -c behat.yml.dist --format=pretty --profile e2e
