.DEFAULT_GOAL := help

# New docker-compose binary check
ifeq (, $(shell which docker-compose))
	DOCKER_COMPOSE=@docker compose
else
	DOCKER_COMPOSE=@docker-compose
endif

DOCKER_COMP			= $(DOCKER_COMPOSE)
DOCKER_COMP_FILES	= -f docker-compose.yml
MAKE				= @make
AGENT_CONT			= $(DOCKER_COMP) exec php-agent
CITOYEN_CONT		= $(DOCKER_COMP) exec php-citoyen
AGENT_NODE_CONT		= $(DOCKER_COMP) exec node-agent
CITOYEN_NODE_CONT	= $(DOCKER_COMP) exec node-citoyen

APP_ENV?=dev

ifeq ($(APP_ENV),prod)
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.prod.yml
else
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.override.yml
endif

ifeq ($(E2E), true)
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.e2e.yml
endif

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

## Display help for project
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
Subdirectory:

## Display help for portail_agent
agent-help:
	@$(MAKE) --no-print-directory -C portail_agent

## Run yarn command in portail_agent
agent-yarn-%:
	@$(AGENT_NODE_CONT) make --no-print-directory $*

## Run in command in portail_agent
agent-%:
	@$(AGENT_CONT) make APP_ENV=$(APP_ENV) --no-print-directory $*

## Connect to portail_agent container
agent-sh:
	@$(AGENT_CONT) sh

## Run composer command in portail_agent container, example: make agent-composer c='req symfony/orm-pack'
agent-composer:
	@$(eval c ?=)
	@$(AGENT_CONT) make --no-print-directory composer c="$(c)"

## Fix linux permissions into agent container
agent-permfix:
	@$(AGENT_CONT) make --no-print-directory permfix uid=$(UID) gid=$(GID)

## Display help for portail_citoyen
citoyen-help:
	@$(MAKE) --no-print-directory -C portail_citoyen

## Run yarn command in citoyen_agent
citoyen-yarn-%:
	@$(CITOYEN_NODE_CONT) make --no-print-directory $*

## Run in command in portail_citoyen
citoyen-%:
	@$(CITOYEN_CONT) make APP_ENV=$(APP_ENV) --no-print-directory $*

## Connect to portail_citoyen container
citoyen-sh:
	@$(CITOYEN_CONT) sh

## Run composer command in portail_citoyen container, example: make citoyen-composer c='req symfony/orm-pack'
citoyen-composer:
	@$(eval c ?=)
	@$(CITOYEN_CONT) make --no-print-directory composer c="$(c)"

## Fix linux permissions into citoyen container
citoyen-permfix:
	@$(CITOYEN_CONT) make --no-print-directory permfix uid=$(UID) gid=$(GID)

#################################
Project:

## Build containers
build:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) build --parallel

## Build containers with debug
build-debug:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) -f docker-compose.debug.yml build --parallel

## Fix linux permissions
permfix: citoyen-permfix
permfix: agent-permfix

## Install environment from scratch
install: build start vendor db-create yarn-install yarn-watch

## Install environment from scratch with debug and tools
install-dev: build-debug start vendor db-create tools-install yarn-install yarn-watch

## Display logs stream
logs:
	@$(eval c ?=)
	@$(DOCKER_COMP) logs -f --tail=0 $(c)

## Start containers
start:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) up -d

## Stop containers
stop:
	@$(DOCKER_COMP) down --remove-orphans

#################################
Composer:

## Install php dependencies
vendor: citoyen-vendor agent-vendor

## Install php tools
tools-install: citoyen-tools-install agent-tools-install

#################################
Yarn:

## Install nodes packages
yarn-install: citoyen-yarn-install agent-yarn-install

## Build dev nodes packages and watch
yarn-watch:
	@$(MAKE) -j2 citoyen-yarn-watch agent-yarn-watch

## Build prod nodes packages
yarn-build: citoyen-yarn-build agent-yarn-build

## Build dev nodes packages
yarn-dev: citoyen-yarn-dev agent-yarn-dev

#################################
Doctrine:

## Drop, create db and create tables
db-create: citoyen-db-create

#################################
QA:

## Run php-cs-fixer
cs: citoyen-cs agent-cs

## Run php magic number detector
mnd: citoyen-mnd agent-mnd

## Run phpstan
phpstan: citoyen-phpstan agent-phpstan

## Run php assumptions
assumption: citoyen-assumption agent-assumption

## Run infection (mutation testing)
infection: citoyen-infection agent-infection

## Run Symfony security checker
security: citoyen-security agent-security

## Run PHP Copy/Paste Detector
cpd: citoyen-cpd agent-cpd

#################################
Tests:

## Run phpunit tests
unit: citoyen-unit agent-unit

#################################
Performance:

## Profile from CLI, pass the parameter "url=" to run a given command, example: make blackfire url='https://localhost'
blackfire:
	$(eval url ?=)
	$(DOCKER_COMP) exec blackfire blackfire curl $(url)
