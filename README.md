# Plaintes en ligne PEL

## Install

Setup env files:

```bash
cp .env.local.dist .env.local # Edit it if needed
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
