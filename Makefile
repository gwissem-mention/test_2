.DEFAULT_GOAL := help

DOCKER_COMP = @docker-compose
DOCKER_COMP_FILES = -f docker-compose.yml

ifeq ($(APP_ENV),prod)
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.prod.yml
else
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.override.yml
endif

CITOYEN_CONT		:= $(DOCKER_COMP) exec php_citoyen
CITOYEN_PHP			:= $(CITOYEN_CONT) php
CITOYEN_COMPOSER	:= $(CITOYEN_CONT) composer
CITOYEN_CONSOLE		:= $(CITOYEN_CONT) console
CITOYEN_SF_CLI		:= $(CITOYEN_CONT) symfony

AGENT_CONT		:= $(DOCKER_COMP) exec php_agent
AGENT_PHP		:= $(AGENT_CONT) php
AGENT_COMPOSER	:= $(AGENT_CONT) composer
AGENT_CONSOLE	:= $(AGENT_CONT) console
AGENT_SF_CLI	:= $(AGENT_CONT) symfony

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

## Build containers
build:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) build --parallel

## Build containers with debug
build-debug:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) -f docker-compose.debug.yml build --parallel

## Fix linux permissions
permfix:
	@$(CITOYEN_CONT) chown -R $(UID):$(GID) .
	@$(AGENT_CONT) chown -R $(UID):$(GID) .

## Install environment from scratch
install: build vendor

## Install environment from scratch with debug and tools
install-dev: build-debug start vendor vendor-tools

## Display logs stream
logs:
	@$(eval c ?=)
	@docker-compose logs -f --tail=0 $(c)

## Connect to PHP container (portail citoyen)
citoyen-sh:
	@$(CITOYEN_CONT) sh

## Connect to PHP container (portail agent)
agent-sh:
	@$(AGENT_CONT) sh

## Start containers
start:
	@docker-compose $(DOCKER_COMP_FILES) up -d

## Stop containers
stop:
	@docker-compose down --remove-orphans

#################################
Composer:

## Install php dependencies
vendor: citoyen-vendor agent-vendor

## Install php tools
tools-install: citoyen-tools-install agent-tools-install

## Run composer for citoyen container, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
citoyen-composer:
	@$(eval c ?=)
	@$(CITOYEN_COMPOSER) $(c)

## Install php dependencies for dev (portail citoyen)
citoyen-vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
citoyen-vendor: citoyen-composer

## Update composer dependencies  (portail citoyen)
citoyen-vendor-update: c=update --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
citoyen-vendor-update: citoyen-composer

## Show recipes  (portail citoyen)
citoyen-recipes: c=recipes
citoyen-recipes: citoyen-composer

## Synchronize the recipes  (portail citoyen)
citoyen-sync-recipes: c=sync-recipes
citoyen-sync-recipes: citoyen-composer

## Show recipes  (portail citoyen)
citoyen-recipes-update: c=recipes:update
citoyen-recipes-update: citoyen-composer

## Install php tools (portail citoyen)
citoyen-tools-install:
	@$(CITOYEN_COMPOSER) install --working-dir=tools/infection
	@$(CITOYEN_COMPOSER) install --working-dir=tools/php-assumptions
	@$(CITOYEN_COMPOSER) install --working-dir=tools/php-cs-fixer
	@$(CITOYEN_COMPOSER) install --working-dir=tools/phpstan
	@$(CITOYEN_COMPOSER) install --working-dir=tools/phpmnd
	@$(CITOYEN_CONT) wget https://phar.phpunit.de/phpcpd.phar -O tools/phpcpd.phar

## Run composer for citoyen container, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
agent-composer:
	@$(eval c ?=)
	@$(CITOYEN_COMPOSER) $(c)

## Install php dependencies for dev (portail citoyen)
agent-vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
agent-vendor: agent-composer

## Update composer dependencies  (portail agent)
agent-vendor-update: c=update --prefer-dist --no-dev --no-progress --no-scripts --no-interaction --optimize-autoloader
agent-vendor-update: agent-composer

## Show recipes  (portail agent)
agent-recipes: c=recipes
agent-recipes: agent-composer

## Synchronize the recipes  (portail agent)
agent-sync-recipes: c=sync-recipes
agent-sync-recipes: agent-composer

## Show recipes  (portail agent)
agent-recipes-update: c=recipes:update
agent-recipes-update: agent-composer

## Install php tools (portail agent)
agent-tools-install:
	@$(AGENT_COMPOSER) install --working-dir=tools/infection
	@$(AGENT_COMPOSER) install --working-dir=tools/php-assumptions
	@$(AGENT_COMPOSER) install --working-dir=tools/php-cs-fixer
	@$(AGENT_COMPOSER) install --working-dir=tools/phpstan
	@$(AGENT_COMPOSER) install --working-dir=tools/phpmnd
	@$(AGENT_CONT) wget https://phar.phpunit.de/phpcpd.phar -O tools/phpcpd.phar

#################################
Tools:

## Run php-cs-fixer
citoyen-cs:
	@$(CITOYEN_CONT) tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run --diff --config .php-cs-fixer.dist.php

## Run php magic number detector
citoyen-mnd:
	@$(CITOYEN_CONT) tools/phpmnd/vendor/bin/phpmnd src

## Run phpstan
citoyen-phpstan:
	@$(CITOYEN_CONT) tools/phpstan/vendor/bin/phpstan analyse -c .phpstan.neon

## Run php assumptions
citoyen-assumption:
	@$(CITOYEN_CONT) tools/php-assumptions/vendor/bin/phpa src

## Run infection (mutation testing)
citoyen-infection:
	@$(CITOYEN_CONT) XDEBUG_MODE=coverage tools/infection/vendor/bin/infection run -j$(NPROC) -s --ignore-msi-with-no-mutations --no-progress -c infection.json

## Run Symfony security checker
citoyen-security:
	@$(CITOYEN_SF_CLI) security:check

## Run PHP Copy/Paste Detector
citoyen-cpd:
	@$(CITOYEN_CONT) php tools/phpcpd.phar src

## Run php-cs-fixer
agent-cs:
	@$(AGENT_CONT) tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run --diff --config .php-cs-fixer.dist.php

## Run php magic number detector
agent-mnd:
	@$(AGENT_CONT) tools/phpmnd/vendor/bin/phpmnd src

## Run phpstan
agent-phpstan:
	@$(AGENT_CONT) tools/phpstan/vendor/bin/phpstan analyse -c .phpstan.neon

## Run php assumptions
agent-assumption:
	@$(AGENT_CONT) tools/php-assumptions/vendor/bin/phpa src

## Run infection (mutation testing)
agent-infection:
	@$(AGENT_CONT) XDEBUG_MODE=coverage tools/infection/vendor/bin/infection run -j$(NPROC) -s --ignore-msi-with-no-mutations --no-progress -c infection.json

## Run Symfony security checker
agent-security:
	@$(AGENT_SF_CLI) security:check

## Run PHP Copy/Paste Detector
agent-cpd:
	@$(AGENT_CONT) php tools/phpcpd.phar src

#################################
Tests:

## Run phpunit tests
unit:
	@$(PHP_CONTAINER_EXEC) bin/phpunit --no-coverage --order-by random --process-isolation -vvv --format=pretty --stop-on-failure
