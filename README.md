# Dockerized Laravel 9 & Vue 3 App with TypeScript
Docker development implementation for Laravel 9.\*, Vue 3, vite, tailwindcss with:

- Nginx
- MySql
- PHP8.2
- Vue
- Node
- Reddis

## Installation

> - Clone this repository `git clone https://github.com/efren-corillo/purchasepro-assesment`
> - Make sure you have docker and docker-compose installed on your machine, 
 you do not need to have php / mysql / redis / node installed on your machine

> - please note that we are just running the db inside docker with a volume. Usually we would 
separate the database from this server and run a different server or service like RDS, dynamoDB, etc..

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
> - To run the site locally you invoke
> - `docker-compose build -d` / `yarn vite` 
> - To build for staging.
> - / `yarn build` / and change the .env APP_ENV=staging || production.
> - You can access the project at: `http://localhost:8000` when developing locally or use the server ip
> - , unless you have add a domain.
> 
> - To initialize laravel in docker initiate:
> - Install composer dependencies: `docker-compose run ppro_app composer install`
> - Install composer dependencies: `docker-compose run ppro_app php artisan key:generate`
> - Install composer dependencies: `docker-compose run ppro_app php artisan optimize`
> - Install composer dependencies: `docker-compose run ppro_app php artisan migrate`
> - Install composer dependencies: `docker-compose run ppro_app php artisan db:seed --class="CatalogsSeeder`
> - This product seeder can be run multiple times to create many products as you want.
> - Install composer dependencies: `docker-compose run ppro_app php artisan db:seed --class="ProductSeeder`
> - Install javascript dependencies: `docker-compose run ppro_npm install`

> ### Demo
> 
> The Application Url:
> http://18.136.193.232/
> 
> Mailhog Url
> http://18.136.193.232:8025/#

> ### Reference
> - https://github.com/efren-corillo/purchasepro-assesment