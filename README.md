# Dockerized Laravel 9 & Vue 3 App with TypeScript

Docker development implementation for Laravel 9.\* with:

- Nginx
- MySql
- PHP8.2
- Vue
- Node
- Reddis

## Installation

> - Clone this repository `git clone https://github.com/efren-corillo/purchasepro-assesment`
> - Make sure you have docker installed on your local machine, you do not need to have php / mysql / redis / node installed on your machine

> - Copy `.env` file: `cp .env.example .env`
> - Set the environment variables in `.env` file
> - Copy `Dockerfile` file: `cp Dockerfile.example Dockerfile`
> - Set the appropriate data in `Dockerfile` file
> - Copy `nginx/site.conf` file: `cp nginx/site.conf.example nginx/site.conf`
> - Set the appropriate data in `nginx/site.conf` file

> - Run command: `docker-compose up --build -d`
> - Run the container in bash mode: `docker exec -it ppro_app /bin/sh`
> - Inside this container now you can run all the commands as if if you are on local environment:
> - Install composer dependencies: `composer install`
> - Generate key: `php artisan key:generate`
> - Run migration: `php artisan migrate`
> - Run seeder: `php artisan db:seed`

> - Install javascript dependencies: `yarn`
> - Compile the assets: `yarn dev` / `yarn watch`  / `dcoker-compose run yarn run vite`
> - You can access the project at: `http://localhost:8000`
> - or
> - Install composer dependencies: `docker-compose run php composer install`
> - Install javascript dependencies: `docker-compose run npm install`

#Reference
- https://github.com/efren-corillo/purchasepro-assesment