# ABRouter-Compose  📟
ABRouter is the open-source tool to perform and track A/B tests which is also known as the experiments.
Additionally, feature flags management system, built-in statistics is also included in the project.

The project provides high level of support for Laravel, Symfony and vanilla PHP.

You're welcome to [visit the docs](https://docs.abrouter.com).


## Features

🛠 A/B Tests

🛠 Feature flags

🛠 Built-in statistics

🛠 Incredible UI to manage it 

## Technologies used

⚙️ Laravel

⚙️ React

⚙️ JQuery

⚙️ Traefik

⚙️ PHP

⚙️ MySQL

⚙️ Consul

⚙️ Redis

⚙️ JSON API

## Core components 

#### Compose
Repository itself. Responsible for managing docker project structure contains docker-compose.yml and essential makefile-commands to set up it.

Containers: traefik, consul, api container, frontend container, Redis, MySQL.

#### [API](https://github.com/abrouter/api)

API repository, based on the Laravel. Communicating via JSON API and REST API.

#### [Front](https://github.com/abrouter/front)
Frontend repository, also based on the Laravel and contains React and vanilla JS.

Could send the requests to abr-api to handle user authorization for respond with partially rendered html.

#### Traefik

Routing all incoming requests between abr-api and abr-front and providing ssl.
It's possible to set up additional services via traefik to make the requests from api or front.

## Requirements

It's possible to set up ABRouter locally or on the remote host on AWS, Digital Ocean, Linode, etc.

Make sure docker and PHP installed on your machine. Recommended versions:

```
docker >= 18.0.0
php >= 5.6.0
docker-compose >= 1.20.0 
```

Currently, public configuration contains config only for manual deploy to the single machine, but it will be enough to handle millions of the api requests with vertical scaling (scaling CPU and RAM of the node that handle requests).

## Deploy

#### 0.Cloning

```
mkdir abr
cd abr
git clone git@github.com:abrouter/abrouter.git
git clone git@github.com:abrouter/api.git
git clone git@github.com:abrouter/front.git
```

#### 1.Docker-compose.yml
Generate the docker-compose.yml file. For development purposes use the following command (workdir compose):
```
cd compose
make build-dev
```

If you want to set up it in the production mode please use the following:
```
make build-prod
```

The difference is in SSL, and ports. You can see it in /build/compose.php.

#### 2. Build and start the containers

```
make up
```

or 

```
docker-compose up -d
```

#### 3. Install PHP dependencies

This command will install the composer dependencies in api and front container.
```
make install
```

#### 4. Seed the consul keys

Next, we have to write the consul keys 
```
make fill-consul
```

#### 5. Generating .env files

This command will generate .env files in both containers.
```
make consul
```

#### 6. Copy dependencies to the local machine from docker

Due to problems with relationship of Mac OS and Docker some files is not mounted to the working volume. Only directories are mounted directly to the container. 
So, you will probably have to perform some additional actions for local development. 
```
cd ../abr-api
make sync-container-to-local
```

This command will copy /vendor, composer.lock, composer.json and .env files to the local machine. Both repositories have this command.

Please, note, when you want to add new dependencies to the project - did "composer require ..." in docker container and then, flush the installed dependencies and composer-files to your local machine to commit it.

The same command, if your composer-files is outdated in container. Then, make install to update dependencies.
```
cd ../abr-api
make sync-local-to-container
```

#### 7. Database

```
docker exec -it abr-mysql sh
mysql -u root -p #password is bestpass
CREATE DATABASE IF NOT EXISTS `abr`;
CREATE DATABASE IF NOT EXISTS `abr_test`;
```

abr_test is using for testing purposes. Database is switching on running the tests.

And migrate:
```
make migrate
```

#### 8. Authorization

Create oauth keys. Files will be created in /app/storage/. Please, be carefully and save oauth-*.key somewhere, because it's not included in the build. Losing this files can cause the loose of access to the accounts on your server. 
```
make passport
```




That's all what we need to deploy ABRouter 🎉. 
