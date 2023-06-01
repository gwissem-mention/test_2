# Plaintes en ligne PEL

## Install

Setup env files:

```bash
 # Edit them if needed
cp docker-compose.override.yml.dist docker-compose.override.yml
cp .env.local.dist .env.local

# In portail_agent & portail_citoyen
cp .env.dist .env.local

```
```bash
#In .env.local in portail agent
add ENABLE_SSO=false
add OODRIVE configuration (It will be communicated to you during the onboarding meeting)
add FILE_UNITS=service.csv (It will be communicated to you during the onboarding meeting. It should be copied to portail_agent/referentials/ )
```
```bash
#In .env.local in portail citoyen

add FRANCE_CONNECT configuration (It will be communicated to you during the onboarding meeting)
    OODRIVE configuration (It will be communicated to you during the onboarding meeting)
add    
    FILE_CITIES=insee_commune.csv 
    FILE_JOBS_MALE=Liste_libelles_professions_hommes_2023.csv
    FILE_JOBS_FEMALE=Liste_libelles_professions_femmes_2023.csv
    FILE_SERVICES=service.csv
    FILE_CITIES_SERVICES=competence_securite_publique.csv
    FILE_UNITS_GN=pwb_ref_unite_gn.csv
    FILE_CITIES_UNITS=pwb_ref_insee_unite.csv
    FILE_DEPARTMENTS=departement_2022.csv
(All this files will be communicated to you during the onboarding meeting. It should be copied to portail_citoyen/referentials/)
```
After adding those files:
```bash
make referential-create-extensions
make agent-db-create-extensions
make agent-referential-load
```
Install the project with following commands:

```bash
make install
```

If you want some extra dev tools, run the command:
```bash
make install-dev
```

If you need to load the fixtures of the project:
```bash
# Citoyen fixtures
make citoyen-referential-create
make citoyen-referential-load

# Agent fixtures
make agent-db-load
```

You can find more commands to use in the [Makefile](./Makefile) file.
Also, each project has his own Makefile too.

use this address to access the agent portail locally
```bash
https://agent.pel.localhost
```
use this address to access the citoyen portail locally
```bash
https://citoyen.pel.localhost
```
## Tests
Run phpunit tests
```bash
make unit
```

Run agent functional tests
```bash
make agent-db-setup agent-db-load APP_ENV=test

make agent-behat
```
Run agent e2e tests
```bash
make start E2E=true
make agent-e2e 
```
### Quality code
```bash
make cs
make phpstan
```

## Performance

Create a `.env.local` file based on the `.env.local.dist` file at the root of the project and copy its content.
This file is ignored by git, thanks to the `.gitignore` file at the root of the project.

Replace `BLACKFIRE_CLIENT_ID` and `BLACKFIRE_CLIENT_TOKEN` with your own credentials on [this page](https://blackfire.io/my/settings/credentials).
For `BLACKFIRE_SERVER_ID` and `BLACKFIRE_SERVER_TOKEN`, you can get these values in the blackfire environment settings of the project.

Install also the [Blackfire Browser extension](https://blackfire.io/docs/integrations/browsers/index) to profile from your browser.

Refresh containers to update configuration:

```bash
make start
```

Don't forget to adapt this command for your environment.

### Profile from browser

1. Login in the extension
2. Click on the button to profile

### Profile from CLI

Run the command:

```bash
make blackfire url='[your-url]'
```

Example for the `agent` project:
```bash
make blackfire url='http://nginx_agent'
```

Example for the `citoyen` project:
```bash
make blackfire url='http://nginx_citoyen'
```

If you want to know more about a project, see [agent](./portail_agent/README.md) or [citoyen](./portail_citoyen/README.md)

