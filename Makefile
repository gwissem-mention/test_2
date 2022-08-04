.DEFAULT_GOAL := help

DOCKER_COMP			= @docker-compose
DOCKER_COMP_FILES	= -f docker-compose.yml
MAKE				= @make
AGENT_CONT			= $(DOCKER_COMP) exec php_agent
CITOYEN_CONT		= $(DOCKER_COMP) exec php_citoyen

ifeq ($(APP_ENV),prod)
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.prod.yml
else
	DOCKER_COMP_FILES := $(DOCKER_COMP_FILES) -f docker-compose.override.yml
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

## Run in command in portail_agent
agent-%:
	@$(AGENT_CONT) make --no-print-directory $*

## Connect to portail_agent container
agent-sh:
	@$(AGENT_CONT) sh

## Run composer command in portail_agent container, example: make agent-composer c='req symfony/orm-pack'
agent-composer:
	@$(eval c ?=)
	@$(AGENT_CONT) make --no-print-directory composer c="$(c)"

## Display help for portail_citoyen
citoyen-help:
	@$(MAKE) --no-print-directory -C portail_citoyen

## Run in command in portail_citoyen
citoyen-%:
	@$(CITOYEN_CONT) make --no-print-directory $*

## Connect to portail_citoyen container
citoyen-sh:
	@$(CITOYEN_CONT) sh

## Run composer command in portail_citoyen container, example: make citoyen-composer c='req symfony/orm-pack'
citoyen-composer:
	@$(eval c ?=)
	@$(CITOYEN_CONT) make --no-print-directory composer c="$(c)"

#################################
Project:

## Build containers
build:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) build --parallel

## Build containers with debug
build-debug:
	@$(DOCKER_COMP) $(DOCKER_COMP_FILES) -f docker-compose.debug.yml build --parallel

## Fix linux permissions
permfix: uid=$(UID)
permfix: gid=$(GID)
permfix: citoyen-permfix
permfix: agent-permfix

## Install environment from scratch
install: build start vendor

## Install environment from scratch with debug and tools
install-dev: build-debug start vendor tools-install

## Display logs stream
logs:
	@$(eval c ?=)
	@$(DOCKER_COMP) logs -f --tail=0 $(c)

## Start containers
start:
	@echo $(DOCKER_COMP_FILES)
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
