# Plaintes en ligne PEL

## Install

Setup env files:

```bash
 # Edit them if needed
cp docker-compose.override.yml.dist docker-compose.override.yml
cp .env.local.dist .env.local
```

Install the project with following commands:

```bash
make install
```

If you want some extra dev tools, run the command:
```bash
make install-dev
```

You can find more commands to use in the [Makefile](./Makefile) file.
Also, each project has his own Makefile too.


## Tests

Run tests:

```bash
make tests
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

### Deploy

#### Deploy into Smile container

/!\ This is not the best way to deploy, but it's the easiest way to deploy for now. You need to be in the Smile VPN (https://wiki.smile.fr/wiki/VPN_Connection) to do this.

```bash
ssh root@pel-citoyen.forge.intranet
# Switch to pel-citoyen user
su pel-citoyen
cd ~/plaintes-en-ligne-pel/
git pull
cd portail_citoyen # For now we use only this project
composer dump-env prod
composer install --no-dev --optimize-autoloader
make install # Yarn dependencies
make build # Build assets + javascript
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```
