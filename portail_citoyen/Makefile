.DEFAULT_GOAL := help

DOCKER_COMP = @docker-compose
DOCKER_COMP_FILES = -f docker-compose.yml

ifeq ($(APP_ENV),prod)
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.prod.yml
else
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.override.yml
endif

PHP_CONT = $(DOCKER_COMP) exec php_citoyen

PHP			:= $(CITOYEN_CONT) php
COMPOSER	:= $(CITOYEN_CONT) composer
CONSOLE		:= $(CITOYEN_CONT) console
SF_CLI		:= $(CITOYEN_CONT) symfony

NPROC	:= 1
OS		:= $(shell uname)
UID		:= $(shell id -u)
GID		:= $(shell id -g)

ifeq ($(OS),Linux)
	NPROC := $(shell nproc)
else ifeq ($(OS),Darwin)
	NPROC := $(shell sysctl -n hw.ncpu)
endif

ifndef CI_JOB_ID
	GREEN	:= $(shell tput -Txterm setaf 2)
	YELLOW	:= $(shell tput -Txterm setaf 3)
	WHITE	:= $(shell tput -Txterm setaf 7)
	RESET	:= $(shell tput -Txterm sgr 0)
	TARGET_MAX_CHAR_NUM=30
endif

help:
	@echo "${GREEN}Plainte en ligne${RESET} https://citoyen.pel.localhost https://agent.pel.localhost"
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
	@$(PHP_CONT) chown -R $(UID):$(GID) .

## Build containers
build:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) build --parallel

## Build containers with debug
build-debug:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) -f docker-compose.debug.yml build

## Install environment from scratch
install: build vendor

## Install environment from scratch with debug and tools
install-dev: build-debug start vendor vendor-tools

## Display logs stream
logs:
	@$(eval c ?=)
	@docker-compose logs -f --tail=0 $(c)

## Connect to PHP container
sh:
	@$(PHP_CONT) sh

## Start containers
start:
	@docker-compose $(DOCKER_COMP_FILES) up -d

## Stop containers
stop:
	@docker-compose down --remove-orphans

#################################
Composer:

## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
composer:
	@$(eval c ?=)
	@$(COMPOSER) $(c)

## Install php dependencies for dev
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
vendor: composer

## Install php dependencies for prod
vendor-prod: c=install --no-dev --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
vendor-prod: composer

## Update composer dependencies
vendor-update: c=update --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
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
vendor-tools:
	@$(COMPOSER) install --working-dir=tools/infection
	@$(COMPOSER) install --working-dir=tools/php-assumptions
	@$(COMPOSER) install --working-dir=tools/php-cs-fixer
	@$(COMPOSER) install --working-dir=tools/phpstan
	@$(COMPOSER) install --working-dir=tools/phpmnd
	@wget https://phar.phpunit.de/phpcpd.phar -O tools/phpcpd.phar

#################################
Tools:

## Run php-cs-fixer
cs:
	@$(PHP_CONTAINER_EXEC) tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run --diff --config .php-cs-fixer.dist.php

## Run php magic number detector
mnd:
	@$(PHP_CONTAINER_EXEC) tools/phpmnd/vendor/bin/phpmnd src

## Run phpstan
phpstan:
	@$(PHP_CONTAINER_EXEC) tools/phpstan/vendor/bin/phpstan analyse -c .phpstan.neon

## Run php assumptions
assumption:
	@$(PHP_CONTAINER_EXEC) tools/php-assumptions/vendor/bin/phpa src

## Run infection (mutation testing)
infection:
	@$(PHP_CONTAINER_EXEC) XDEBUG_MODE=coverage tools/infection/vendor/bin/infection run -j$(NPROC) -s --ignore-msi-with-no-mutations --no-progress -c infection.json

## Run Symfony security checker
security:
	@$(SF_CLI) security:check

## Run PHP Copy/Paste Detector
cpd:
	@$(PHP_CONTAINER_EXEC) php tools/phpcpd.phar src

#################################
Tests:

## Run phpunit tests
unit:
	@$(PHP_CONTAINER_EXEC) bin/phpunit --no-coverage --order-by random --process-isolation -vvv --format=pretty --stop-on-failure
