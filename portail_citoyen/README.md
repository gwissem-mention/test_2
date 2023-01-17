# Plaintes en ligne PEL - Portail citoyen

## Getting Started

Please follow the root [README.md](../README.md) file.

This is based on top of [Symfony Docker](https://github.com/dunglas/symfony-docker).
Check their docs if you need more informations.

### Deploy

#### Ansible's installation (only on your computer)

#### MacOS

Install Ansible with Homebrew :
```bash
$ brew install ansible
```

#### Linux 

https://docs.ansible.com/ansible/latest/installation_guide/installation_distros.html


Install ansible requirements :
```bash
$ ansible-galaxy install -r ansible/requirements.yaml
```

#### Deployment

The deployment is actually made with [Ansible](https://docs.ansible.com/ansible/latest/index.html) and [Ansistrano](https://github.com/ansistrano/deploy).
Everything is automated for deployment, but if you need to deploy manually, follow these steps :

You have to be connected to the [Smile VPN](https://wiki.smile.fr/wiki/VPN_Connection).

Your local SSH public key has to be added to your Wombat profile.

You will also need the **config/secrets/prod/prod.decrypt.private.php** file in your local

In portail_citoyen/ folder :

```bash
$ BECOME_USER=[SERVER_USER] PRIVATE_KEY_PATH=[YOUR_PRIVATE_KEY_PATH] make deploy
```

#### Rollback

```bash
$ BECOME_USER=[SERVER_USER] PRIVATE_KEY_PATH=[YOUR_PRIVATE_KEY_PATH] make rollback
```

#### Add sensitive env var for production server

First, you will need the **config/secrets/prod/prod.decrypt.private.php** file in your local

Then will need to add this env with [Symfony Secrets](https://symfony.com/doc/current/configuration/secrets.html) :

```bash
$ APP_RUNTIME_ENV=prod php bin/console secrets:set [YOUR_ENV]
```
