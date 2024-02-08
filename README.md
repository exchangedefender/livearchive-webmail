# LiveArchive Webmail

## TLDR / I want it now

1. Setup [LiveArchive](https://exchangedefender.com/docs/livearchive-cloud-backend)

2. Start a docker container instance
```shell
docker run -d --rm -p 8008:80 exchangedefender/livearchive-webmail:latest
```

Browse to http://localhost:8008

Be aware that this quick method does not persist any data and will be reset when the docker container is stopped. Follow the [installation](#installation) instructions for a production setup.

## Overview

LiveArchive Webmail is a php based archive mailbox viewer for [LiveArchive](https://exchangedefender.com/docs/livearchive-cloud-backend). By utilizing object storage users are able to self store archive data in their own cloud buckets with ease. Administrators can **OPTIONALLY** enhance the performance by enabling a SQL backend to provide additional features like filtering, searching and faster archive browsing.

LiveArchive is designed to use one bucket for all users. In order to partition LiveArchive across multiple buckets, you would need to run multiple instances of LiveArchive. LiveArchive will scan the root of the object store and list all available mailboxes. LiveArchive uses the industry standard [Maildir](https://en.wikipedia.org/wiki/Maildir) format to archive messages in the object store. Users are able to export individual messages to `.eml` format or  the entire archive in `.eml` format.

LiveArchive provides no login interface and authentication and authorization should happen by the webserver, firewall or ingress rules with your provider (out of scope for this document), however, since LiveArchive running in docker is powered by [frankenphp](https://frankenphp.dev), you can easily configure [basic authentication](docs/frankenphp.md#enabling-basic-auth) within the embedded [caddy](https://caddyserver.com) webserver.

TLS and HTTPS is also out of scope for this application, but as above, using our docker image there is a way to enable following [these steps](docs/frankenphp.md#enabling-ssl)

## Quick Start

Chose an installation method below (compose, docker or source) and then browse to the site (default http://localhost). 

The first request to the site will automatically redirect to `/_` which will perform a one time initialization (setting up encryption keys). 

Once the site is initialized, you will be automatically redirect to `/setup/` to start the setup wizard if an existing configuration was not found.

## Setup Wizard

The setup wizard allows you to configure the archive dynamically instead of modifying environment variables or individual configuration files. Upon completion of each step the setup wizard will update the configuration loaded by `LIVEARCHIVE_PERSISTENCE` and the sites local .env file  

### .env file
While this is a [laravel](https://laravel.com) based application, be aware that the setup wizard will overwrite the provided values from the wizard to the env file. You can disable the site from changing the .env file by setting the environment variable `LIVEARCHIVE_ENV_SYNC` to `false` or `0`

## Installation
The quickest way to get started is to run the prebuilt docker images along with one of the sample docker compose files.

Choose one of the below installation methods

### Docker Compose

We have two sample docker compose files inside the repository. Use [docker-compose.standalone.yml](docker-compose.standalone.yml) if you already have object storage created (S3, etc) or use [docker-compose.sample.yml](docker-compose.sample.yml) to create an all-in-one setup which includes minio for the object storage and mysql for the database storage.

LiveArchive Webmail persists global configuration data under `/app/storage/app/settings`.

Here is a minimum service entry
```yaml
services:
    app:
        image: exchangedefender/livearchive-webmail:latest
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        ports:
            - '80:80'
        volumes:
            - ./livearchive-config:/app/storage/app/settings
            - ./livearchive-data:/data
        environment:
          LIVEARCHIVE_PERSISTENCE: disk
```

### Docker

`docker run -p 80:80 exchangedefender/livearchive:latest`

### Source

Requirements:
- webserver (frankenphp, apache, caddy, phpcgi etc)
- nodejs (to build assets)
- PHP 8.2 with the following modules
  - pdo_mysql
  - intl
  - mailparse
  - zip

Clone the repository and `composer install` the required PHP dependencies. 

Next, run `npx vite build` to build the sites assets.

Finally, run `artisan storage:link` 

## Configuration Files

LiveArchive persists the configuration to JSON after running the initial setup wizard and loads the configuration when browsing the mail archive.

LiveArchive offers two built in persistence adapters, the default local file system and one via cookies in the browser. 

To enable storing the configuration in the browser set the environment variable `LIVEARCHIVE_PERSISTENCE` to `browser`

If no configuration is found then LiveArchive attempts to use the config files under `config/` and environment variables as the default values


## Environment Variables

Most of these are configured during the [Setup Wizard](#setup-wizard) and do not need to be set manually. However, the values for the below env variables are used to initially populate the [configuration files](#configuration-files) 

| Name                    | Default       | Notes                                                                                                                 |
|-------------------------|---------------|-----------------------------------------------------------------------------------------------------------------------|
| AWS_ACCESS_KEY_ID       | NONE          | Bucket access key                                                                                                     |
| AWS_SECRET_ACCESS_KEY   | NONE          | Bucket secret access key                                                                                              |
| AWS_DEFAULT_REGION      | NONE          | Bucket region                                                                                                         |
| AWS_BUCKET              | NONE          | Bucket name                                                                                                           |
| AWS_URL                 | NONE          | Set to public address for minio if not using AWS                                                                      |
| LIVEARCHIVE_ENV_SYNC    | `true`        | allow the site to manage the .env file when running the setup wizard                                                  |
| LIVEARCHIVE_PERSISTENCE | `disk`        | `disk` or `browser` to switch between using a central file for all users or each browser stores its own configuration |
| LIVEARCHIVE_LAYOUT      | `gmail`       | `gmail` or `office` to switch between different layout styles                                                         |
| LIVEARCHIVE_DB_HOST     | NONE          | address for livearchive mysql backend if enabled                                                                      |
| LIVEARCHIVE_DB_PORT     | `3306`        | port number for livearchive mysql backend                                                                             |
| LIVEARCHIVE_DB_DATABASE | NONE          | database name for livearchive mysql backend                                                                           |
| LIVEARCHIVE_DB_USERNAME | NONE          | username to connect to livearchive mysql backend                                                                      |
| LIVEARCHIVE_DB_PASSWORD | NONE          | password to connect to livearchive mysql backend                                                                      |
| APP_NAME                | `LiveArchive` | application name used in various areas like the title bar and headers.                                                |
| APP_TIMEZONE            | `UTC`         | local timezone to convert to when showing dates                                                                       |






## Development setup
1. cd to this project in your terminal
2. install php prerequisites for sail
    ```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ``` 
3. start sail
    ```shell
    ./vendor/bin/sail up -d
    ./vendor/bin/sail artisan storage:link
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
    ```
4. run the migrations
    ```shell
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan migrate --database=livearchive
    ```

# Resuming work

1. start sail
    ```shell
    ./vendor/bin/sail up -d
    ./vendor/bin/sail npm run dev
    ```

# Notes

1. The first 5 results from the DB have their times dynamically changed so we can work with the different formatting options. This will be removed on release. To disable it edit `ArchiveFileSystem` and look for '//TODO remove.. just here for previewing timestamp with relative time' 
2. To disable DB usage just set LIVEARCHIVE_DB_DATABASE to an empty value. This will cause the web ui to only use S3
3. Search is only available when backed by a database (ie not s3 only mode)

# Adding new actions
1. Add the route to `routes/web.php`
2. Add the controller logic (currently we're only using `app/Http/Controllers/ArchiveInboxController`)

# Helpful links
- Blade (ui engine in laravel) https://laravel.com/docs/10.x/blade
- URL generation https://laravel.com/docs/10.x/urls
- Request/form/post validation https://laravel.com/docs/10.x/validation
